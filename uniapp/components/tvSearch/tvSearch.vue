<template>
	<view class="cu-modal bottom-modal" :class="isShow && isshow?'show':''">
		<view class="cu-dialog">
			<view class="cu-bar bg-my-gray line-bottom-my-gray">
				<view class="action text-my-white">设备列表</view>
				<view class="action text-gray" @tap="hideModal"><text class="cuIcon-close"></text></view>
			</view>
			<view class="cu-list menu sm-border bg-my-gray" :style="modalStyle">
				<view class="cu-item bg-my-gray line-bottom-my-gray-sm" v-for="(item,index) in tvLists" :key="index" @click="sendTotv(index)">
					<view class="content">
						<text class="text-grey">{{item.name}}</text>
					</view>
				</view>
				<view class="cu-load bg-my-gray loading" v-if="showLoading"></view>
			</view>
			<view class="line-top-my-gray" @click="stopSearch()">
				<view class="bg-my-gray search-btn" v-text="btnText"></view>
			</view>
		</view>
	</view>
</template>

<script>
	// #ifdef APP-VUE
	const lyzmlDLNA = uni.requireNativePlugin("lyzml-DLNA");  
	// #endif
	export default {
		props: {
			isShow: {
				type: Boolean,
				default: function() {
					return false;
				}
			},
			videoUrls: String
		},
		data() {
			return {
				windowInfo: {
					statusBarHeight: 0,
					windowHeight: 0,
					windowWidth: 0,
					trueHeight: 0
				},
				modalStyle: '',
				myEventManager: null,
				eventManager: null,
				tvLists: null,
				isshow: true,
				toastTitle: '搜寻失败',
				showLoading: true,
				isStop: true,
				btnText: '停止搜寻',
				isClose:false
			};
		},
		mounted() {
			var _this = this;
			_this.init();
			uni.$on('tvModelInit',function(data){
				_this.myEventManager = null;
				_this.eventManager= null;
				_this.tvLists = null;
				_this.isshow = true;
				_this.toastTitle = '搜寻失败';
				_this.showLoading = true;
				_this.isStop = true;
				_this.btnText = '停止搜寻';
				_this.isClose = false;
				_this.startSearch();
			})
			uni.$on('stopTvPlay',function(){
				_this.stopVideo();
			})
		},
		methods:{
			init() {
				var _this = this;
				const res = uni.getSystemInfoSync();
				_this.windowInfo.statusBarHeight = res.statusBarHeight;
				_this.windowInfo.windowHeight = res.windowHeight;
				_this.windowInfo.trueHeight = _this.windowInfo.windowHeight - _this.windowInfo.statusBarHeight;
				// modal样式
				_this.modalStyle = 'height:'+(_this.windowInfo.trueHeight - (_this.windowInfo.trueHeight/2.2))+'px; overflow-y: auto;';
			},
			startSearch () {
				lyzmlDLNA.startSearch((devList)=>{
					this.tvLists = devList;
					console.log("====startSearch====",JSON.stringify(devList));
				});
			},
			hideModal() {
				this.isshow = false;
				lyzmlDLNA.stopSearch();
				this.showLoading = false;
				this.isClose = true;
			},
			sendTotv (index) {
				var _this = this;
				lyzmlDLNA.playVideo({
					ip: _this.tvLists[index].ip,
					mediaURL: _this.videoUrls
				},(resp)=>{
					console.log("====playVideo=====",resp);
					if(resp.code == 0){
						_this.stopSearch();
						_this.hideModal();
						uni.$emit('showTvMask');
					}
				});
			},
			stopVideo(){
				console.log("====stopVideo====");
				lyzmlDLNA.stopVideo();
			},
			stopSearch () {
				if (this.isStop) {
					lyzmlDLNA.stopSearch();
					this.toastTitle = '停止搜寻';
					this.showLoading = false;
					this.btnText = '重新搜寻';
					this.isStop = false;
				}else{
					this.startSearch();
					this.btnText = '停止搜寻';
					this.isStop = true;
					this.showLoading = true;
				}
			}
		}
	}
</script>

<style>
	.cu-load.loading::after {
	    content: "\641c\5bfb\4e2d..."!important;
	}
	.search-btn {
		text-align: center;
		padding: 40upx;
	}
</style>
