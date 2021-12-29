<template>
	<view class="pages">
		<view class="flex" v-if="downloadList.length > 0">
			<button class="cu-btn block line-myblue lg flex-sub margin-xs" @tap="startAll()">
				<view v-if="isStart">
					<text class="cuIcon-playfill"></text> 全部开始
				</view>
				<view v-else>
					<text class="cuIcon-stop"></text> 全部暂停
				</view>
			</button>
			<button class="cu-btn block line-myblue lg flex-sub margin-xs">
				同时下载数
			</button>
		</view>
		<view class="download-list" v-if="downloadList.length > 0">
			<view class="" v-for="(item, index) in downloadList" :key="index" @tap="resumeById(item.id, item.status)" @longpress="taskController(item)">
				<view class="text-lg padding-tb-15 flex justify-between">
					<view class="margin-xs download-list-text">
						<text class="text-black">{{item.name}}</text>
					</view>
					<view class="margin-xs" v-if="item.status == 'COMPLETED'">
						<text class="cuIcon-videofill line-blue" @click="play(item.name)" style="font-size: 15px;min-width: 100px;"> 播放</text> 
					</view>
				</view>
				<view class="cu-progress progress-heigth round">
					<view class="bg-myblue" :style="[{ width:((item.currentLength / item.totalLength) * 100) + '%' }]"></view>
				</view>
				<view class="text-xs flex justify-between">
					<text class="text-gray padding-tb-5 margin-xs" v-if="item.status == 'IDLE'">未下载</text>
					<text class="text-gray padding-tb-5 margin-xs" v-if="item.status == 'WAITING'">等待</text>
					<text class="text-gray padding-tb-5 margin-xs" v-if="item.status == 'CONNECTING'">连接中</text>
					<text class="text-gray padding-tb-5 margin-xs" v-if="item.status == 'CONNECT_SUCCESSFUL'">连接成功</text>
					<text class="text-gray padding-tb-5 margin-xs" v-if="item.status == 'DOWNLOADING'">正在下载...</text>
					<text class="text-gray padding-tb-5 margin-xs" v-if="item.status == 'PAUSED'">已暂停</text>
					<text class="text-gray padding-tb-5 margin-xs" v-if="item.status == 'CANCELLED'">已取消</text>
					<text class="text-gray padding-tb-5 margin-xs" v-if="item.status == 'COMPLETED'">已完成</text>
					<text class="text-gray padding-tb-5 margin-xs" v-if="item.status == 'ERROR'">错误</text>
					<text class="text-gray padding-tb-5 margin-xs">
						<!-- {{ item.status == "DOWNLOADING" ? ( item.speed > 0 ? item.speed + "m/s" : oldSpeed + "m/s") : "" }} --> {{item.currentLength}}M/{{item.totalLength}}M
					</text>
				</view>
			</view>
		</view>
		<view class="download-list" v-else>
			<view class="padding-xs flex align-center">
				<view class="flex-sub text-center">
					<view class="text-df padding">暂无下载任务</view>
				</view>
			</view>
		</view>
		
		<view class="cu-modal" :class="modalName=='TaskModel'?'show':''" @tap="hideModal" v-if="taskInfo">
			<view class="cu-dialog" @tap.stop="">
				<view class="cu-bar bg-white solid-bottom">
					<view class="text-cut">
						{{ taskInfo.name }}
					</view>
				</view>
				<view class="cu-list menu sm-border">
					<view class="cu-item" v-if="taskInfo.status == 'PAUSED' || taskInfo.status == 'CANCELLED' || taskInfo.status == 'ERROR'">
						<view class="content" @click="taskTap(1)">
							<text class="text-grey" v-if="taskInfo.status == 'ERROR'">重新下载</text>
							<text class="text-grey" v-else>恢复任务</text>
						</view>
					</view>
					<view class="cu-item" v-if="taskInfo.status == 'DOWNLOADING' || taskInfo.status == 'WAITING' || taskInfo.status == 'CONNECT_SUCCESSFUL' 
						|| taskInfo.status == 'CONNECTING'
						">
						<view class="content" @click="taskTap(2)">
							<text class="text-grey">暂停任务</text>
						</view>
					</view>
					<view class="cu-item" v-if="taskInfo.status == 'PAUSED' || taskInfo.status == 'CONNECT_SUCCESSFUL'
						|| taskInfo.status == 'CONNECTING' || taskInfo.status == 'WAITING'
						">
						<view class="content" @click="taskTap(3)">
							<text class="text-grey">取消任务</text>
						</view>
					</view>
					<view class="cu-item" v-if="taskInfo.status == 'COMPLETED' || taskInfo.status == 'ERROR' || taskInfo.status == 'PAUSED'">
						<view class="content" @click="taskTap(4)">
							<text class="text-grey">删除任务</text>
						</view>
					</view>
				</view>
			</view>
		</view>
		
		<view class="cu-modal" :class="modalName=='DeleteTask'?'show':''">
			<view class="cu-dialog">
				<view class="cu-bar bg-white justify-end">
					<view class="content">删除提示</view>
				</view>
				<view class="padding-xl">
					是否同时删除文件？
				</view>
				<view class="cu-bar bg-white">
					<view class="action margin-0 flex-sub" @tap="deleteById(true)">删除</view>
					<view class="action margin-0 flex-sub solid-left" @tap="deleteById()">不删除</view>
					<view class="action margin-0 flex-sub solid-left" @tap="hideModal">取消</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import hex_md5 from '@/common/md5.js';
	export default {
		data() {
			return {
				downloadList:[],
				isListener: false,
				taskList: [],
				taskIdList: [],
				taskStart:false,
				oldSpeed: 0,
				oldCurrentSize: 0,
				modalName: '',
				taskInfo: null,
				isStart: false
			}
		},
		onShow() {
			var _this = this;
			_this.isStart = uni.getStorageSync('isStartDownload');
			_this.queryAll();
			setTimeout(function(){
				_this.listener();
			}, 200);
		},
		onUnload() {
			
		},
		methods: {
			play (name) {
				uni.navigateTo({
				    url: '/pages/download/player?name=' + name,
					animationDuration: 100
				});
			},
			startAll () {
				var _this = this;
				if (!_this.isStart) {
					_this.isStart = true;
					_this.pauseAll();
				}else{
					_this.isStart = false;
					_this.recoverAll();
				}
				uni.setStorageSync('isStartDownload', _this.isStart);
			},
			taskController(item) {
				this.modalName = "TaskModel";
				this.taskInfo = item;
			},
			taskTap (type) {
				var _this = this;
				let id = _this.taskInfo.id;
				switch(type){
					case 1:
						_this.resumeById(id);
						_this.modalName = null;
						setTimeout(function(){
							_this.queryAll();
						}, 800);
						break;
					case 2:
						_this.pauseById(id);
						_this.modalName = null;
						_this.isStart = true;
						uni.setStorageSync('isStartDownload', _this.isStart);
						setTimeout(function(){
							_this.queryAll();
						}, 800);
						break;
					case 3:
						_this.cancelById(id);
						_this.modalName = null;
						setTimeout(function(){
							_this.queryAll();
						}, 800);
						break;
					case 4:
						_this.modalName = "DeleteTask";
						break;
				}
			},
			hideModal () {
				this.modalName = "";
			},
			queryAll () {
				var _this = this;
				_this.$downloader.queryAll(function(res){
					_this.downloadList = JSON.parse(res);
				});
			},
			recoverAll () {
				this.$downloader.recoverAll();
				this.listener();
			},
			pauseAll () {
				this.$downloader.pauseAll();
			},
			stopListener () {
				this.isListener = false;
				this.$downloader.stopListener();
			},
			deleteAll() {
				// 删除之前若有任务在进行中，请先暂停全部任务再进行删除操作
				this.$downloader.deleteAll(true);
				this.queryAll();
				this.taskList = [];
				this.taskIdList= [];
				this.taskStart= false;
			},
			deleteById(delete_file) {
				var _this = this;
				if (delete_file) {
					_this.$downloader.deleteById(_this.taskInfo.id, delete_file, _this.taskInfo.name);
				}else{
					_this.$downloader.deleteById(_this.taskInfo.id);
				}
				setTimeout(function(){
					_this.queryAll();
					_this.hideModal();
				}, 400)
			},
			pauseById(id) {
				this.$downloader.pauseById(id);
			},
			resumeById(id, status) {
				if (status != "COMPLETED"){
					this.$downloader.resumeById(id);
				}
			},
			cancelById(id) {
				this.$downloader.cancelById(id);
			},
			listener () {
				var _this = this;
				if(!_this.isListener){
					
					_this.$downloader.queryAll(function(res){
						let list = JSON.parse(res);
						if (!_this.isStart && list.length == 0) {
							_this.downloadList = [];
						}
					});
					
					
					_this.$downloader.listener(function(res){
						_this.isListener = true;
						let _res = JSON.parse(res);
						if (_res instanceof Object) {
							let _speed = _res.current_size - _this.oldCurrentSize;
								_speed = _speed <= 0 ? _this.oldSpeed : _speed
							let obj = {
								id: _res.id,
								name: _res.save_name,
								status: _res.status,
								currentLength: _res.current_size,
								totalLength: _res.total_size,
								speed: _speed
							};
							_this.oldCurrentSize = _res.current_size;
							_this.oldSpeed = _speed;
							// 添加进下载队列
							_this.taskList.push(obj);
							// 查询队列id里是否已有此任务id，没有则在下载列表里追加一条任务
							if (_this.taskIdList.indexOf(obj.id) == -1) {
								_this.taskIdList.push(_res.id);
								_this.downloadList.push(obj);
							}
							// 渲染列表
							if(!_this.taskStart){
								_this.taskStart = true;
								_this.doTask();
							}
						}
					});
				}
			},
			doTask () {
				var _this = this;
				let taskTimer = setInterval(function(){
					let obj = _this.taskList[0];
					if (obj instanceof Object) {
						// 查询任务id列表内是否有下载id并返回索引
						let index = _this.taskIdList.indexOf(obj.id);
						// 更新下载列表数据
						_this.downloadList.find(function(value, index){
							if (value.id == obj.id) {
								_this.$set(_this.downloadList, index, obj);
							}
						})
						// 剔除一条下载任务
						_this.taskList.shift();
						// 下载任务列表为空，停止数据更新
						if(_this.taskList.length == 0){
							_this.isStart = true;
							uni.setStorageSync('isStartDownload', _this.isStart);
							_this.taskStart = false;
							// 从数据库中查询任务列表，保证下载任务是最新并准确的
							_this.queryAll();
							clearInterval(taskTimer);
						}
					}else{
						_this.isStart = true;
						uni.setStorageSync('isStartDownload', _this.isStart);
						_this.taskStart = false;
						// 从数据库中查询任务列表，保证下载任务是最新并准确的
						_this.queryAll();
						clearInterval(taskTimer);
					}
				}, 400);
			},
		},
	}
</script>

<style>
</style>
