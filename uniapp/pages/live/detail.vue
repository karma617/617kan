<template>
	<view class="page" :style="bodyMinHeight">
		<block v-if="!isShowxx">
			<!-- 播放器 -->
			<view class="player-box bg-my-gray minHeight">
				<!-- #ifdef APP-PLUS -->
					<block v-if="tvMask">
						<view :style="videoStyle">
							<view class="tv-mask" :style="tvMaskBgStyle">
								<view class="tv-mask-title">投屏中</view>
								<view class="tv-mask-btn">
									<view @tap="sendtv"><text class="cuIcon-order"></text> 切换</view>
									<view @tap="exittv"><text class="cuIcon-exit"></text> 退出</view>
								</view>
							</view>
						</view>
					</block>
					<block v-else>
						<view v-if="videoUrl" :style="videoStyle">
							<video
								id="myVideo"
								:src="videoUrl"
								@error="videoErrorCallback"
								:initial-time="currentTime"
								:show-mute-btn="true"
								controls
								:style="videoStyle"
								:autoplay="true"
								:title="videoInfo.title"
								:enable-play-gesture="true"
								:vslide-gesture="true"
								:page-gesture="true"
								@fullscreenchange="listenFullScreen"
							>
							</video>
						</view>
					</block>
				<!-- #endif -->
				<!-- #ifdef MP-WEIXIN -->
					<view v-if="videoUrl">
						<video
							id="myVideo"
							:show-casting-button="true"
							:show-screen-lock-button="true"
							:src="videoUrl"
							@error="videoErrorCallback"
							:initial-time="currentTime"
							:show-mute-btn="true"
							controls
							:style="videoStyle"
							:autoplay="true"
							:title="videoInfo.title"
							:enable-play-gesture="true"
							:vslide-gesture="true"
							enable-danmu
							:page-gesture="true"
							:ad-unit-id="videoAd"
							@fullscreenchange="listenFullScreen"
						>
						</video>
					</view>
				<!-- #endif -->
				<!-- #ifdef MP-QQ -->
					<view v-if="videoUrl">
						<video
							id="myVideo"
							:show-casting-button="true"
							:show-screen-lock-button="true"
							:src="videoUrl"
							@error="videoErrorCallback"
							:initial-time="currentTime"
							:show-mute-btn="true"
							controls
							:style="videoStyle"
							:autoplay="true"
							:title="videoInfo.title"
							:enable-play-gesture="true"
							:vslide-gesture="true"
							enable-danmu
							:page-gesture="true"
							:ad-unit-id="videoAd"
							@fullscreenchange="listenFullScreen"
						>
						</video>
					</view>
				<!-- #endif -->
				<!-- #ifdef H5 -->
					<view :style="videoStyle" id="myVideo"></view>
				<!-- #endif -->
			</view>
			<!-- 内容 -->
			<!-- #ifdef H5 -->
			<view :style="contentStyle">
			<!-- #endif -->
			<!-- #ifndef H5 -->
			<view class="padding-top-3" :style="contentStyle">
			<!-- #endif -->
				<view class="solid-bottom">
					<view class="cu-bar">
						<view class="action fl">
							<text class="text-lg">{{liveInfo.title}}</text>
						</view>
						<view class="fr">
						</view>
					</view>
				</view>
				<view class="solid-bottom">
					<view class="cu-bar xuanji">
						<view class="action fl">
							<text class="text-lg">切换源</text>
						</view>
						<view class="fr">
							<text class="text-grey text-df video-more-text">共{{liveInfo.videoYuan}}个源</text>
						</view>
					</view>
					<view class='padding-sm flex flex-wrap grid col-5'>
						<scroll-view scroll-x class="nav" scroll-with-animation :scroll-left="scrollLeft">
							<view class="cu-item video-level cu-tag" :class="index == TabCur ? 'bg-blue' : 'light bg-grey'" v-for="(item,index) in liveInfo.url_list" :key="index" @tap="tabSelect" :data-id="index">
								{{item.name}}
							</view>
						</scroll-view>
					</view>
				</view>
				<!-- 底部广告 -->
				<view class="player-bottom-ad padding-5" v-if="playerBottomAD.isshow && playerBottomAD.ad_type < 3">
					<image :src="playerBottomAD.img" @click="goBottomAd()"></image>
				</view>
				<Adview :adSwitchKg="playerBottomAD.isshow" :homeADList="playerBottomAD" v-else></Adview>
				<!-- #ifdef APP-PLUS -->
				<tvSearch :isShow="tvIsShow" :videoUrls="videoUrl"></tvSearch>
				<!-- #endif -->
				<!-- 广告弹窗 -->
				<uni-popup v-if="playerAd.ad_type != 5" ref="image" type="center" :show="showAdModel" :custom="true" :mask-click="false">
					<view class="uni-image" :style="playerADStyle">
						<view class="uni-image-close" @click="playerADCancel('image')">
							<uni-icons type="clear" color="#dddddd" size="25" />
						</view> 
						<image class="image" :src=" playerAd.img ? apiImageUrl + playerAd.img : '/static/ad.png'" mode="" @click="goAd"/>
					</view>
				</uni-popup>
				<!-- 菜单modal -->
				<view class="cu-modal bottom-modal" :class="modalName=='titleNviewButton'?'show':''">
					<view class="cu-dialog xuanji-box bg-my-gray" :style="menuBoxStyle">
						<view class="cu-bar line-bottom-my-gray">
							<view class="action text-gray">菜单</view>
							<view class="action text-gray" @tap="hideModal"><text class="cuIcon-close"></text></view>
						</view>
						<view class="cu-list menu sm-border" style="width: 100%;">
							<view class="cu-item arrow bg-my-gray line-bottom-my-gray-sm">
								<button class="cu-btn content" @tap="sendtv">
									<text class="cuIcon-btn text-olive"></text>
									<text class="text-grey">投屏</text>
								</button>
							</view>
							<!-- <view class="cu-item arrow"> -->
								<!-- #ifdef APP-PLUS -->
								<!-- <button class="cu-btn content" @tap="shareBtn">
									<text class="cuIcon-share text-olive"></text>
									<text class="text-grey">分享</text>
								</button> -->
								<!-- #endif -->
							<!-- </view> -->
						</view>
					</view>
				</view>
			</view>
		</block>
		<block v-else>
			<view class="mask">
				<view class="text-df refresh-text text-gray">
					<image class="refresh-img" src="../../static/noinfo.png"></image>
					<view class="" >敬请期待</view>
				</view>
			</view>
		</block>
	</view>
</template>

<script>
	// 小程序插屏广告
	let interstitialAd = null;
	import hex_md5 from '@/common/md5.js';
	import uniPopup from "@/components/uni-popup/uni-popup.vue";
	import uniIcon from '@/components/uni-icon/uni-icon.vue';
	import tvSearch from '@/components/tvSearch/tvSearch.vue';
	import Adview from '@/components/home-ad/ad-view.vue';
	export default {
		components: {
			uniPopup,
			uniIcon,
			tvSearch,
			Adview
		},
		data() {
			return {
				tvMaskBgStyle:'',
				menuBoxStyle: '',
				isShowxx: false,
				videoAd: '',
				tvMaskStyle: '',
				imageUrl: this.$helper.imageUrl,
				apiImageUrl: this.$helper.apiImageUrl,
				danmuContent: '',
				showAdModel: false,
				playerADImg: '',
				playerADStyle: '',
				xuanjiBoxStyle: '',
				xuanjiItemBoxStyle: '',
				modalName: '',
				bodyMinHeight: '',
				scrollLeft: 0,
				TabCur: 0,
				isback: false,
				isLike: false,
				videoInfo: {
					title: '',
					playIndex: 0
				},
				liveInfo: null,
				vod_id: 0,
				videoType: 0,
				vid: 0,
				videoStyle: '',
				videoUrl: '',
				currentTime: 0,
				videoContext: null,
				gobackView: null,
				windowInfo: {
					statusBarHeight: 0,
					windowHeight: 0,
					windowWidth: 0,
					trueHeight: 0
				},
				contentStyle: '',
				playerAd: {
					img: '',
					url: 'http://www.baidu.com'
				},
				playerBottomAD: {
					img: '/static/ad.png',
					url: 'http://www.baidu.com',
					isshow: 0
				},
				tvIsShow: false,
				tvTitle: '正在搜寻设备...',
				isIos: this.$os == "ios",
				tvMask: false
			}
		},
		onShow() {
			let config = uni.getStorageSync('sys_config');
			this.isShowxx = isNaN(parseInt(config.show_xx)) ? true : parseInt(config.show_xx);
		},
		onLoad() {
			let liveInfo = uni.getStorageSync('liveInfo');
			this.liveInfo = liveInfo;
			this.liveInfo.videoYuan = liveInfo.url_list.length;
			// #ifndef H5
				this.videoContext = uni.createVideoContext('myVideo');
			// #endif
		},
		onNavigationBarButtonTap(){
			// #ifndef APP-PLUS
				uni.showModal({
					title: '提示',
					content: '该功能只支持APP端',
					showCancel: false,
					confirmColor: "#45506a"
				})
				return false;
			// #endif
			this.modalName = "titleNviewButton";
		},
		mounted() {
			this.init();
			let _this = this;
			uni.$on('showTvMask',function(){
				_this.tvMask = true;
				setTimeout(function(){
					_this.videoContext.pause();
				}, 500)
			})
		},
		methods:{
			videoErrorCallback (e) {
				console.log(e);
			},
			listenFullScreen(event) {
				if (event.detail.fullScreen) {
					
				}
			},
			hideModal() {
				this.modalName = null
			},
			shareBtn(){
				let _this = this;
				// 该功能为测试功能，因目前没有微信开放平台帐号，无法继续完善
				uni.share({
				    provider: "weixin",
				    scene: "WXSceneSession",
				    type: 0,
				    href: _this.$helper.shareUrl,
				    title: _this.videoInfo.title,
				    success: function (res) {
				        console.log("success:" + JSON.stringify(res));
				    },
				    fail: function (err) {
				        console.log("fail:" + JSON.stringify(err));
				    }
				});
			},
			showModal(e) {
				this.modalName = e.currentTarget.dataset.target;
			},
			titleNview (title){
				let pages = getCurrentPages();
				let page = pages[pages.length - 1];
				let currentWebview = page.$getAppWebview();
				let tn = currentWebview.getStyle().titleNView;
				tn.titleText = title;
				currentWebview.setStyle({
				    titleNView: tn
				});
			},
			closeTvMask () {
				this.tvMask = false;
				this.videoContext.play();
				uni.$emit('stopTvPlay');
			},
			sendtv (){
				// #ifdef APP-PLUS
					var _this = this;
					_this.tvIsShow = true;
					_this.hideModal();
					uni.$emit('tvModelInit');
				// #endif
			},
			exittv () {
				this.tvIsShow = false;
				this.tvMask = false;
				uni.$emit('stopTvPlay');
				this.videoContext.play();
			},
			playerADCancel () {
				let _this = this;
				// 是否显示弹窗广告
				let sysconf = uni.getStorageSync('sys_config');
				if(sysconf.ad_close == 1) {
					_this.goAd();
				}else{
					_this.$refs['image'].close();
					_this.drowVideoBox();
				}
			},
			drowVideoBox () {
				// #ifdef APP-PLUS
				this.videoStyle = 'position:absolute;background: rgb(0, 0, 0);z-index:1;width:100%;height:'+(this.windowInfo.trueHeight/3.4)+'px;';
				this.tvMaskBgStyle = 'height:'+(this.windowInfo.trueHeight/3.4)+'px;';
				// #endif
				// #ifdef MP
				this.videoStyle = 'position:absolute;background: rgb(0, 0, 0);z-index:1;width:100%;height:'+(this.windowInfo.trueHeight/3)+'px;';
				// #endif
				this.palyer();
			},
			goBottomAd () {
				let _this = this;
				// #ifdef H5
					window.open(this.playerBottomAD.url);
				// #endif
				// #ifndef H5
					plus.runtime.openWeb(this.playerBottomAD.url);
				// #endif
			},
			init () {
				var _this = this;
				const res = uni.getSystemInfoSync();
				_this.windowInfo.statusBarHeight = res.statusBarHeight;
				_this.windowInfo.windowHeight = res.windowHeight;
				_this.windowInfo.windowWidth = res.windowWidth;
				_this.windowInfo.trueHeight = _this.windowInfo.windowHeight - _this.windowInfo.statusBarHeight;
				// #ifdef MP
					let menuButtonInfo = uni.getMenuButtonBoundingClientRect();
					_this.windowInfo.trueHeight = _this.windowInfo.windowHeight - _this.windowInfo.statusBarHeight - menuButtonInfo.height;
				// #endif
				
				// #ifdef APP-PLUS
				// 顶部遮罩高度
				if (_this.isShowxx) {
					_this.statusMaskTop = 'text-align: center;';
				}
				// #endif
				
				_this.bodyMinHeight = 'min-height:'+_this.windowInfo.windowHeight+'px;overflow: hidden;';
				
				// 内容容器
				// #ifdef H5
				_this.contentStyle = 'overflow-y:auto; z-index:0;position:relative;height: ' + (_this.windowInfo.trueHeight - (_this.windowInfo.trueHeight/3.4)) + 'px';
				// #endif
				
				// #ifndef H5
					_this.contentStyle = 'overflow-y:auto; z-index:0;position:relative; height: 100vh;padding-top: ' + (_this.windowInfo.trueHeight/3.4) + 'px';
					// #ifdef MP
						_this.contentStyle = 'overflow-y:auto; z-index:0;position:relative; height: 100vh;padding-top: ' + (_this.windowInfo.trueHeight/3) + 'px';
					// #endif
					if (_this.isShowxx) {
						_this.contentStyle = 'overflow-y:auto; z-index:0;position:relative;height: ' + _this.windowInfo.trueHeight + 'px';
						// #ifdef MP
						_this.contentStyle = 'overflow-y:auto; z-index:0;position:relative;height: ' + (_this.windowInfo.trueHeight - (_this.windowInfo.trueHeight/3.4)) + 'px';
						// #endif
					}
				// #endif
				
				// 选集高度
				_this.xuanjiBoxStyle = 'max-height: ' + (_this.windowInfo.trueHeight - (_this.windowInfo.trueHeight/3)) + 'px;';
				_this.menuBoxStyle = 'height: ' + (_this.windowInfo.trueHeight - (_this.windowInfo.trueHeight/3)) + 'px;';
				_this.xuanjiItemBoxStyle = 'max-height: ' + (_this.windowInfo.trueHeight - (_this.windowInfo.trueHeight/3) - 100) + 'px;';
				// 广告图片大小
				_this.playerADStyle = 'z-index:10000;width: ' + (_this.windowInfo.windowWidth - 65) + 'px; height: ' + (_this.windowInfo.trueHeight - 150) + 'px;';
				// 投屏遮罩
				_this.tvMaskStyle = 'width: 100%;text-align: center;line-height: 400upx;background-color: #000000;font-size: 18px;color: #ffffff;height: ' + (_this.windowInfo.trueHeight/3.4) + 'px';
				// 广告
				_this.showAd();
			},
			goAd () {
				// #ifdef H5
					window.open(this.playerAd.url);
				// #endif
				// #ifndef H5
					plus.runtime.openWeb(this.playerAd.url);
				// #endif
			},
			showAd() {
				let _this = this;
				// 是否显示弹窗广告
				let sysconf = uni.getStorageSync('sys_config');
				if (sysconf.player_show_ad == 1) {
					// 请求服务器，获取弹窗广告和底部
					_this.$http.post('/index/home/getPlayerAdList',{}).then((res)=>{
						if (res.data.alert_ad.hasOwnProperty('img')) {
							uni.setStorageSync('adInfo', res.data);
							if (res.data.alert_ad.ad_type != 5) {
								_this.showAdModel = true;
								_this.playerAd = res.data.alert_ad;
								_this.$refs['image'].open();
							} else {
								// #ifdef MP-WEIXIN
									// 创建插屏组件
									if (wx.createInterstitialAd) {
										interstitialAd = wx.createInterstitialAd({
											adUnitId: res.data.alert_ad.url
										})
										// 15秒内只能弹一次
										setTimeout(function(){
											interstitialAd.show();
										}, 15500);
										interstitialAd.onLoad(() => {})
										interstitialAd.onError((err) => {})
										interstitialAd.onClose(() => {})
									}
								// #endif
							}
						}
						if (sysconf.player_bottom_ad == 1 && res.data.alert_public.hasOwnProperty('img')) {
							_this.playerBottomAD = res.data.alert_public;
							_this.playerBottomAD.img = _this.apiImageUrl + res.data.alert_public.img;
							_this.playerBottomAD.url = res.data.alert_public.url;
							_this.playerBottomAD.isshow = 1;
						}
						if (sysconf.show_video_ad == 1 && res.data.video_ad.hasOwnProperty('img')) {
							// #ifdef MP
								_this.videoAd = res.data.video_ad.url;
							// #endif
						}
						_this.drowVideoBox();
					}).catch((err)=>{
						
					})
				}else if (sysconf.player_bottom_ad == 1) {
					// 请求服务器，获取弹窗广告和底部
					_this.$http.post('/index/home/getPlayerAdList',{}).then((res)=>{
						if (res.data.alert_public.hasOwnProperty('img')) {
							uni.setStorageSync('adInfo', res.data);
							_this.playerBottomAD = res.data.alert_public;
							_this.playerBottomAD.img = _this.apiImageUrl + res.data.alert_public.img;
							_this.playerBottomAD.url = res.data.alert_public.url;
							_this.playerBottomAD.isshow = 1;
						}else{
							_this.playerBottomAD.isshow = 0;
						}
						if (sysconf.show_video_ad == 1 && res.data.video_ad.hasOwnProperty('img')) {
							// #ifdef MP
								_this.videoAd = res.data.video_ad.url;
							// #endif
						}
						_this.drowVideoBox();
					}).catch((err)=>{
						
					})
				} else if (sysconf.show_video_ad == 1) {
					_this.$http.post('/index/home/getPlayerAdList',{}).then((res)=>{
						uni.setStorageSync('adInfo', res.data);
						if (res.data.video_ad.hasOwnProperty('img')) {
							// #ifdef MP
								_this.videoAd = res.data.video_ad.url;
							// #endif
						}
						_this.drowVideoBox();
					}).catch((err)=>{
						
					})
				} else {
					// 视频容器
					_this.drowVideoBox();
				}
			},
			palyer () {
				let _this = this;
				_this.videoUrl = _this.liveInfo.url_list[0].url;
				_this.videoInfo.title = _this.liveInfo.title;
				// 设置H5端播放器
				// #ifdef H5
					const dp = new DPlayer({
					    container: document.getElementById('myVideo'),
						autoplay: true,
					    video: {
					        url: _this.videoUrl,
					        type: 'hls'
					    }
					});
				// #endif
				
				// #ifndef H5
				// 视频信息
				setTimeout(function(){
					_this.changeVideoInfo(_this.TabCur);
				}, 1300)
				// #endif
			},
			tabSelect(e) {
				this.TabCur = e.currentTarget.dataset.id;
				this.scrollLeft = (e.currentTarget.dataset.id - 1) * 60
				this.videoContext.stop();
				this.changeVideoInfo(e.currentTarget.dataset.id)
			},
			changeVideoInfo(index){
				var _this = this;
				_this.TabCur = index;
				_this.videoUrl = _this.liveInfo.url_list[index].url;
				// #ifndef H5
					// 视频信息
					setTimeout(function(){
						_this.videoContext.play();
					}, 1000)
				// #endif
			},
		},
	}
</script>

<style>
	.page{
		background-color: #161827!important;
	}
	/* 插屏广告 */
	.uni-image {
		position: relative;
	}
	
	.uni-image .image {
		width: 100%;
		height: 100%;
	}
	
	.uni-image-close {
		position: absolute;
		right: 0px;
		top: 0px;
		z-index: 2;
	}
	.player-bottom-ad image{
		width: 100%;
		height: 270upx;
	}
	
	cover-view,
	cover-image {
		display: inline-block;
	}
	
	/* .tv-img {
		position: absolute;
		width: 60rpx;
		height: 50rpx;
		top: 5%;
		left: 200px;
		z-index: 99999;
	} */
	.controls-sendtv{
		color: #FFFFFF;
		width: 60upx;
		height: 40upx;
		right: 35upx;
		top: 45upx;
		position: absolute;
	}
	.video-title {
		flex-direction: row!important;
		width: 90%;
		justify-content: space-between!important;
	}
	.showxx-title {
		text-align: center;
	}
	.xuanji-bottom {
		padding: 30upx;
	}
	.xuanji-box {
		border-top-left-radius: 10upx!important;
		border-top-right-radius: 10upx!important;
	}
	.xuanji-item {
		border-radius: 10upx!important;
	}
	.xuanji-item-box {
		overflow-y: auto;
	}
	.nav .cu-item{
		height: 30px!important;
		line-height: 30px!important;
	}
	.tv-mask {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		font-size: 32upx;
		background-image: url("data:image/jpg;base64,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");
		background-position: center;
		background-repeat: no-repeat;
		background-size: 100% 100%;
		width: 100%;
	}
	.tv-mask-title {
		font-size: 40upx;
	}
	.tv-mask-btn {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		width: 40%;
		padding: 72upx 0;
	}
	.rate{
	  position: absolute;
	  width: 100rpx;
	  height: auto;
	  font-size: 22rpx;
	  text-align: center;
	  display:box;
	  display:-webkit-box;
	  display:-webkit-flex;
	  display:-moz-box;
	  display:-ms-flexbox;
	  display:flex;
	  flex-wrap: wrap;
	  flex-direction: row;
	  overflow: hidden;
	  top:21%;
	  right:-100rpx;
	  border-top-left-radius: 20rpx;
	  border-bottom-left-radius: 20rpx;
	  align-items: center;
	  justify-content: center;
	  background-color: rgba(0,0,0,0.6);
	}
	.rateBtn{
	  position: absolute;
	  width: 100rpx;
	  height: 60rpx;
	  font-size: 22rpx;
	  display:box;
	  display:-webkit-box;
	  display:-webkit-flex;
	  display:-moz-box;
	  display:-ms-flexbox;
	  display:flex;
	  flex-wrap: wrap;
	  flex-direction: row;
	  overflow: hidden;
	  text-align: center;
	  right: 0rpx;
	  right:-100rpx;
	  border-top-left-radius: 20rpx;
	  border-bottom-left-radius: 20rpx;
	  top:40%;
	  align-items: center;
	  justify-content: center;
	  background-color: rgba(0,0,0,0.6);
	}
	.numb{
	  font-size: 24rpx;
	  color:#fff;
	  width: 90rpx;
	  height: 60rpx;
	  line-height: 60rpx;
	  border-top-left-radius: 20rpx;
	  border-bottom-left-radius: 20rpx;
	  display:box;
	  display:-webkit-box;
	  display:-webkit-flex;
	  display:-moz-box;
	  display:-ms-flexbox;
	  display:flex;
	  align-items: center;
	  justify-content: center;
	}
</style>
