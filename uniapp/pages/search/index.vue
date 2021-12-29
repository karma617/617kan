<template>
	<view class="content pages">
		<view class="search-box">
			<!-- mSearch组件 如果使用原样式，删除组件元素-->
			<mSearch class="mSearch-input-box" :mode="2" button="inside" :placeholder="defaultKeyword" @search="inputChange" @input="inputChange" @confirm="inputChange" v-model="keyword"></mSearch>
			<!-- 原样式 如果使用原样式，恢复下方注销代码 -->
			<!-- 			
			<view class="input-box">
				<input type="text" :placeholder="defaultKeyword" @input="inputChange" v-model="keyword" @confirm="doSearch(false)"
				 placeholder-class="placeholder-class" confirm-type="search">
			</view>
			<view class="search-btn" @tap="doSearch(false)">搜索</view> 
			-->
			<!-- 原样式 end -->
		</view>
		<view class="search-keyword" @touchstart="blur">
			<scroll-view class="keyword-list-box" v-show="isShowKeywordList" scroll-y>
				<view class="keyword-entry" hover-class="keyword-entry-tap" v-for="(row,index) in keywordList" :key="index">
					<view class="keyword-text" @tap="doSearch(row.keyword)">
						<rich-text class="rich-text" :nodes="row.htmlStr"></rich-text>
					</view>
					<view class="keyword-img" @tap="setkeyword(row)">
						<image src="/static/HM-search/back.png"></image>
					</view>
				</view>
			</scroll-view>
			<scroll-view class="keyword-box" v-show="!isShowKeywordList" scroll-y>
				<view class="keyword-block" v-if="oldKeywordList.length>0">
					<view class="keyword-list-header">
						<view>历史搜索</view>
						<view>
							<image @tap="oldDelete" src="/static/HM-search/delete.png"></image>
						</view>
					</view>
					<view class="keyword">
						<view v-for="(keyword,index) in oldKeywordList" @tap="doSearch(keyword)" :key="index">{{keyword}}</view>
					</view>
				</view>
				<view class="keyword-block">
					<view class="keyword-list-header">
						<view>热门搜索</view>
						<view>
							<image @tap="hotToggle" :src="'/static/HM-search/attention'+forbid+'.png'"></image>
						</view>
					</view>
					<view class="keyword" v-if="forbid==''">
						<view v-for="(keyword,index) in hotKeywordList" @tap="doSearch(keyword.vod_name)" :key="index">{{keyword.vod_name}}</view>
					</view>
					<view class="hide-hot-tis" v-else>
						<view>当前搜热门搜索已隐藏</view>
					</view>
				</view>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	//引用mSearch组件，如不需要删除即可
	import mSearch from '@/components/mehaotian-search-revision/mehaotian-search-revision.vue';
	export default {
		data() {
			return {
				defaultKeyword: "",
				keyword: "",
				oldKeywordList: [],
				hotKeywordList: [],
				keywordList: [],
				forbid: '',
				isShowKeywordList: false,
				global: {
					lastTime: 0, //此变量用来记录上次摇动的时间
					intervalTime: 500, // 两次摇一摇的间隔事件
					lastX: 0,
					lastY: 0,
					lastZ: 0, //此组变量分别记录对应x、y、z三轴的数值和上次的数值
					shakeSpeed: 2000 //设置阈值
				}
			}
		},
		onLoad() {
			this.init();
		},
		onHide() {
			this.keyword = '';
			uni.stopAccelerometer();
		},
		components: {
			//引用mSearch组件，如不需要删除即可
			mSearch
		},
		onShow() {
			let config = uni.getStorageSync('sys_config');
			this.global.shakeSpeed = config.shake;
			// 监听摇一摇事件
			uni.onAccelerometerChange(this.starshake);
		},
		onBackPress() {
			uni.switchTab({
				url: '/pages/index/index'
			})
			return true;
		},
		methods: {
			starshake(acceleration) {
				let nowTime = new Date().getTime(); //记录当前时间
				let self = this;
				//如果这次摇的时间距离上次摇的时间有一定间隔 才执行
				if (nowTime - this.global.lastTime > 100) {
					let diffTime = nowTime - this.global.lastTime; //记录时间段
					this.global.lastTime = nowTime; // 记录本次摇动时间，为下次计算摇动时间做准备
					let x = acceleration.x; // 获取x轴数值，x轴为垂直于北轴，向东为正
					let y = acceleration.y; // 获取y轴数值，y轴向正北为正
					let z = acceleration.z; // 获取z轴数值，z轴垂直于地面，向上为正
			
					// 速度计算
					let speed =
						(Math.abs(
							x + y + z - self.global.lastX - self.global.lastY - self.global.lastZ
						) /
							diffTime) *
						10000;
					console.log(speed, this.global.shakeSpeed);
					//如果计算出来的速度超过了阈值，那么就算作用户成功摇一摇
					if (speed > this.global.shakeSpeed) {
						uni.stopAccelerometer();
						// 监听是否登录
						let userinfo = uni.getStorageSync('userInfo');
						if (!userinfo) {
							uni.navigateTo({
								url: '/pages/user/login'
							})
						}else{
							uni.navigateTo({
							    url: '/pages/analysis/index'
							});
						}
					}
					//赋值，为下一次计算做准备
					this.global.lastX = x;
					this.global.lastY = y;
					this.global.lastZ = z;
				}
			},
			init() {
				this.loadDefaultKeyword();
				this.loadOldKeyword();
				this.loadHotKeyword();

			},
			blur(){
				uni.hideKeyboard()
			},
			//加载默认搜索关键字
			loadDefaultKeyword() {
				//定义默认搜索关键字，可以自己实现ajax请求数据再赋值,用户未输入时，以水印方式显示在输入框，直接不输入内容搜索会搜索默认关键字
				this.defaultKeyword = "填写关键字";
			},
			//加载历史搜索,自动读取本地Storage
			loadOldKeyword() {
				uni.getStorage({
					key: 'OldKeys',
					success: (res) => {
						var OldKeys = JSON.parse(res);
						this.oldKeywordList = OldKeys;
					}
				});
			},
			//加载热门搜索
			loadHotKeyword() {
				var _this = this;
				//定义热门搜索关键字，可以自己实现ajax请求数据再赋值
				_this.$http.post('/videos/videos/hotKeyWords',{}).then((res)=>{
					if(res.data.list.length > 0){
						_this.hotKeywordList = res.data.list;
					}
				}).catch((err)=>{
					
				})
			}, 
			searchButton () {
				var _this = this;
				//兼容引入组件时传入参数情况
				var keyword = _this.keyword;
				if (!keyword || keyword == ' ') {
					_this.keywordList = [];
					_this.isShowKeywordList = false;
					return;
				}
				_this.isShowKeywordList = true;
				
				_this.$http.post('/videos/videos/searchKeywords',{keyword: keyword}).then((res)=>{
					if(res.data.length > 0){
						_this.keywordList = _this.drawCorrelativeKeyword(res.data, keyword);
					}
				}).catch((err)=>{
					
				})
			},
			//监听输入
			inputChange(event) {
				var _this = this;
				//兼容引入组件时传入参数情况
				var keyword = event.detail?event.detail.value:event;
				if (!keyword || keyword == ' ') {
					_this.keywordList = [];
					_this.isShowKeywordList = false;
					return;
				}
				_this.isShowKeywordList = true;
				_this.keyword = keyword;
				
				_this.$http.post('/videos/videos/searchKeywords',{keyword: keyword}).then((res)=>{
					if(res.data.length > 0){
						_this.keywordList = _this.drawCorrelativeKeyword(res.data, keyword);
					}
				}).catch((err)=>{
					
				})
				// uni.request({
				// 	url: 'https://suggest.taobao.com/sug?code=utf-8&q=' + keyword, //仅为示例
				// 	success: (res) => {
				// 		this.keywordList = this.drawCorrelativeKeyword(res.result, keyword);
				// 	}
				// });
			},
			//高亮关键字
			drawCorrelativeKeyword(keywords, keyword) {
				var len = keywords.length,
					keywordArr = [];
				for (var i = 0; i < len; i++) {
					var row = keywords[i];
					//定义高亮#ff0000
					var html = row.vod_name.replace(keyword, "<span style='color: #dae1f2;'>" + keyword + "</span>");
					html = '<div style="width:100%;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">' + html + '</div>';
					var tmpObj = {
						keyword: row.vod_name,
						htmlStr: html
					};
					keywordArr.push(tmpObj)
				}
				return keywordArr;
			},
			//顶置关键字
			setkeyword(data) {
				this.keyword = data.keyword;
			},
			//清除历史搜索
			oldDelete() {
				uni.showModal({
					content: '确定清除历史搜索记录？',
					success: (res) => {
						if (res.confirm) {
							this.oldKeywordList = [];
							uni.removeStorage({
								key: 'OldKeys'
							});
						} else if (res.cancel) {
							console.log('用户点击取消');
						}
					}
				});
			},
			//热门搜索开关
			hotToggle() {
				this.forbid = this.forbid ? '' : '_forbid';
			},
			//执行搜索
			doSearch(key) {
				key = key ? key : this.keyword ? this.keyword : this.defaultKeyword;
				this.keyword = key;
				this.saveKeyword(key); //保存为历史 
				uni.navigateTo({url:"../detail/index?id="+key});
			},
			//保存关键字到历史记录
			saveKeyword(keyword) {
				uni.getStorage({
					key: 'OldKeys',
					success: (res) => {
						var OldKeys = JSON.parse(res);
						var findIndex = OldKeys.indexOf(keyword);
						if (findIndex == -1) {
							OldKeys.unshift(keyword);
						} else {
							OldKeys.splice(findIndex, 1);
							OldKeys.unshift(keyword);
						}
						//最多10个纪录
						OldKeys.length > 10 && OldKeys.pop();
						uni.setStorage({
							key: 'OldKeys',
							data: JSON.stringify(OldKeys)
						});
						this.oldKeywordList = OldKeys; //更新历史搜索
					},
					fail: (e) => {
						var OldKeys = [keyword];
						uni.setStorage({
							key: 'OldKeys',
							data: JSON.stringify(OldKeys)
						});
						this.oldKeywordList = OldKeys; //更新历史搜索
					}
				});
			}
		}
	}
</script>
<style>
	view{display:block;}
	.search-box {width:100%;background-color:#161827;padding:15upx 2.5%;display:flex;justify-content:space-between;}
	.search-box .mSearch-input-box{width: 100%;}
	.search-box .input-box {width:85%;flex-shrink:1;display:flex;justify-content:center;align-items:center;}
	.search-box .search-btn {width:15%;margin:0 0 0 2%;display:flex;justify-content:center;align-items:center;flex-shrink:0;font-size:28upx;color:#dae1f2;background:linear-gradient(to right,#ff9801,#ff570a);border-radius:60upx;}
	.search-box .input-box>input {width:100%;height:60upx;font-size:32upx;border:0;border-radius:60upx;-webkit-appearance:none;-moz-appearance:none;appearance:none;padding:0 3%;margin:0;background-color:#161827;}
	.placeholder-class {color:#9e9e9e;}
	.search-keyword {width:100%;background-color:#161827;}
	.keyword-list-box {height:calc(100vh - 110upx);padding-top:10upx;border-radius:20upx 20upx 0 0;background-color:#161827;}
	.keyword-entry-tap {background-color:#161827;}
	.keyword-entry {width:94%;height:80upx;margin:0 3%;font-size:30upx;color:#a1a6b3;display:flex;justify-content:space-between;align-items:center;border-bottom:solid 1upx #262a44;}
	.keyword-entry image {width:60upx;height:60upx;}
	.keyword-entry .keyword-text,.keyword-entry .keyword-img {height:80upx;display:flex;align-items:center;}
	.keyword-entry .keyword-text {width:90%;}
	.rich-text{width: 100%;}
	.keyword-entry .keyword-img {width:10%;justify-content:center;}
	.keyword-box {height:calc(100vh - 110upx);border-radius:20upx 20upx 0 0;background-color:#161827;}
	.keyword-box .keyword-block {padding:10upx 0;}
	.keyword-box .keyword-block .keyword-list-header {width:94%;padding:10upx 3%;font-size:27upx;color:#dae1f2;display:flex;justify-content:space-between;}
	.keyword-box .keyword-block .keyword-list-header image {width:40upx;height:40upx;}
	.keyword-box .keyword-block .keyword {width:94%;padding:3px 3%;display:flex;flex-flow:wrap;justify-content:flex-start;}
	.keyword-box .keyword-block .hide-hot-tis {display:flex;justify-content:center;font-size:28upx;color:#dae1f2;}
	.keyword-box .keyword-block .keyword>view {display:flex;justify-content:center;align-items:center;border-radius:60upx;padding:0 20upx;margin:10upx 20upx 10upx 0;height:60upx;font-size:28upx;background-color:#45506a;color:#dae1f2;}
</style>
