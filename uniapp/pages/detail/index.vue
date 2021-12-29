<template>
	<view class="page" :style="bodyMinHeight">
		<!-- 播放器 -->
		<view class="player-box bg-my-gray minHeight" v-if="(!isShowxx || (isShowxx && !isMp)) && !showAdModel">
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
							:danmu-list="videoInfo.danmuList"
							danmu-btn
							:title="videoInfo.title"
							:enable-play-gesture="true"
							:vslide-gesture="true"
							enable-danmu
							:page-gesture="true"
							:ad-unit-id="videoAd"
							@fullscreenchange="listenFullScreen"
							@timeupdate="playing"
							@waiting="waiting"
							@ended="playnext"
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
						:danmu-list="videoInfo.danmuList"
						danmu-btn
						:title="videoInfo.title"
						:enable-play-gesture="true"
						:vslide-gesture="true"
						enable-danmu
						:page-gesture="true"
						:ad-unit-id="videoAd"
						@fullscreenchange="listenFullScreen"
						@timeupdate="playing"
						@controlstoggle="controlstoggle"
						@ended="playnext"
						@play="onPlay"
						@waiting="onWaiting"
					>
						<cover-view class="rateBtn" :animation="animationMenu">
							<cover-view class="numb" @tap="translateX">倍速{{rate}}</cover-view>
							<cover-view v-if="videoInfo.type != 1" @tap="choseRate" class="numb" data-rate="0">下一集</cover-view>
						</cover-view>
						<cover-view class="rate" @tap="choseRate" :animation="animation">
							<cover-view class="numb" data-rate="0.5">x0.5</cover-view>
							<cover-view class="numb" data-rate="1.0">x1.0</cover-view>
							<cover-view class="numb" data-rate="1.5">x1.5</cover-view>
							<cover-view class="numb" data-rate="2.0">x2.0</cover-view>
						</cover-view>
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
						:danmu-list="videoInfo.danmuList"
						danmu-btn
						:title="videoInfo.title"
						:enable-play-gesture="true"
						:vslide-gesture="true"
						enable-danmu
						:page-gesture="true"
						:ad-unit-id="videoAd"
						@fullscreenchange="listenFullScreen"
						@timeupdate="playing"
						@ended="playnext"
					>
						<cover-view class="rateBtn" :animation="animationMenu">
							<cover-view class="numb" @tap="translateX">倍速{{rate}}</cover-view>
							<cover-view v-if="videoInfo.type != 1" @tap="choseRate" class="numb" data-rate="0">下一集</cover-view>
						</cover-view>
						<cover-view class="rate" @tap="choseRate" :animation="animation">
							<cover-view class="numb" data-rate="0.5">x0.5</cover-view>
							<cover-view class="numb" data-rate="1.0">x1.0</cover-view>
							<cover-view class="numb" data-rate="1.5">x1.5</cover-view>
							<cover-view class="numb" data-rate="2.0">x2.0</cover-view>
						</cover-view>
					</video>
				</view>
			<!-- #endif -->
			<!-- #ifdef MP-TOUTIAO -->
				<view v-if="videoUrl">
					<video
						id="myVideo"
						:src="videoUrl"
						:show-play-btn="true"
						:autoplay="true"
						@error="videoErrorCallback"
						controls
						:style="videoStyle"
						:title="videoInfo.title"
						@bindfullscreenchange="listenFullScreen"
						@bindtimeupdate="playing"
						@ended="playnext"
					>
						<cover-view class="rateBtn" :animation="animationMenu">
							<cover-view class="numb" @tap="translateX">倍速{{rate}}</cover-view>
							<cover-view v-if="videoInfo.type != 1" @tap="choseRate" class="numb" data-rate="0">下一集</cover-view>
						</cover-view>
						<cover-view class="rate" @tap="choseRate" :animation="animation">
							<cover-view class="numb" data-rate="0.5">x0.5</cover-view>
							<cover-view class="numb" data-rate="1.0">x1.0</cover-view>
							<cover-view class="numb" data-rate="1.5">x1.5</cover-view>
							<cover-view class="numb" data-rate="2.0">x2.0</cover-view>
						</cover-view>
					</video>
				</view>
			<!-- #endif -->
			<!-- #ifdef H5 -->
				<view :style="videoStyle" id="myVideo"></view>
			<!-- #endif -->
		</view>
		<view class="player-box minHeight showxx-title" :style="isShowxx ? statusMaskTop : ''" v-else>
			<image :lazy-load="true" style="width: 50%;" :src="(videoInfo.vod_pic && videoInfo.vod_pic.substr(0,4) == 'http') ? videoInfo.vod_pic : imageUrl + videoInfo.vod_pic" ></image>
		</view>
		
		<!-- 内容 -->
		<view :style="contentStyle">
			<!-- #ifndef H5 -->
				<!-- #ifndef MP-TOUTIAO -->
					<!-- 弹幕 -->
					<view class="solid-bottom" v-if="!isShowxx">
						<view class="cu-form-group">
							<input placeholder="发个弹幕聊一聊" name="input" v-model="danmuContent"></input>
							<button class='cu-btn bg-my-red shadow' @click="sendDanmu">发送</button>
						</view>
					</view>
				<!-- #endif -->
			<!-- #endif -->
			
			<!-- 播放源 -->
			<view class="solid-bottom" v-if="!isShowxx">
				<view class="cu-bar xuanji">
					<view class="action fl">
						<text class="text-sm">播放源</text>
					</view>
				</view>
				<view class='padding-sm flex flex-wrap grid col-5'>
					<scroll-view scroll-x class="nav" scroll-with-animation :scroll-left="scrollLeft">
						<view class="cu-item video-level cu-tag" 
							:class="item == vodPlayFromTab ? 'bg-my-gray-cur' : 'light bg-my-gray'" 
							v-for="(item,index) in vodPlayFrom" 
							:key="index" 
							@tap="tabPlayFromSelect(item)" 
							:data-id="index"
						>
							{{item}}
						</view>
					</scroll-view>
				</view>
			</view>
			
			<!-- 剧集/源 -->
			<view class="solid-bottom" v-if="!isShowxx">
				<view class="cu-bar xuanji" v-if="videoInfo.type != 1">
					<view class="action fl nav-arrow">
						<text class="text-sm">选集</text>
					</view>
					<view class="fr" @tap="showModal" data-target="chooseModal">
						<text class="text-grey text-sm video-more-text">共{{vodPlayFromTab != '' && videoInfo.srcList[vodPlayFromTab].length}}集</text>
					</view>
				</view>
				<view class="cu-bar xuanji" v-else>
					<view class="action fl nav-arrow">
						<text class="text-sm">切换线路</text>
					</view>
					<view class="fr" @tap="showModal" data-target="chooseModal">
						<text class="text-grey text-sm video-more-text">共{{vodPlayFromTab != '' && videoInfo.srcList[vodPlayFromTab].length}}个</text>
					</view>
				</view>
				<view class='padding-sm flex flex-wrap grid col-5 ' :class="cssAnimation ? 'right-animation' : ''">
					<scroll-view scroll-x class="nav" scroll-with-animation :scroll-left="scrollLeft">
						<view class="cu-item video-level cu-tag" 
							:class="index == TabCur ? 'bg-my-gray-cur' : 'light bg-my-gray'" 
							v-for="(item,index) in videoInfo.srcList[vodPlayFromTab]" 
							:key="index" 
							@tap="tabSelect" 
							:data-id="index"
						>
							{{item.name}}
						</view>
						
					</scroll-view>
				</view>
			</view>
			
			<view class="solid-bottom">
				<!-- 标题 -->
				<view class="cu-bar">
					<view class="action fl video-title">
						<text class="text-lg">{{videoInfo.title}}</text>
						<text class="text-lg">评分：{{videoInfo.vod_score || 0.0}}</text>
					</view>
					<view class="fr">
						<!-- <text class="text-grey text-df video-more-text">简介</text> -->
					</view>
				</view>
				<!-- 简介 -->
				<view class="video-desc-box">
					<view class="padding-10 video-desc text-sm">
						{{videoInfo.remark}}
					</view>
				</view>
				
				<!-- #ifndef H5 -->
				<!-- 点赞 下载 -->
					<view class="padding-tb-20" v-if="!isIos && !isShowxx">
						<!-- <view @click="userLike" class="user-like">
							<uni-text class="lg padding-lr-15" :class="isLike ? 'cuIcon-likefill text-orange' : 'cuIcon-like text-gray'"><span class="padding-lr-5">喜欢</span></uni-text>
						</view> -->
						<!-- #ifndef MP -->
						<view @click="showModal" class="user-download" data-target="downLoadModal">
							<uni-text class="lg text-gray cuIcon-down padding-lr-15 "><span  class="padding-lr-5">下载</span></uni-text>
						</view>
						<!-- #endif -->
						<!-- <uni-text class="lg text-gray cuIcon-share padding-lr-15"><span  class="padding-lr-5">分享</span></uni-text> -->
					</view>
				<!-- #endif -->
			</view>
			
			<!-- 底部广告 -->
			<view class="player-bottom-ad padding-5" v-if="playerBottomAD.isshow && playerBottomAD.ad_type < 3">
				<image :src="playerBottomAD.img" @click="goBottomAd()"></image>
			</view>
			<Adview :adSwitchKg="playerBottomAD.isshow" :homeADList="playerBottomAD" v-else></Adview>
			
			<view>
				<view class="cu-bar">
					<view class="action fl">
						<text class="text-lg">为您推荐</text>
					</view>
				</view>
				<view class="">
					<view class="flex flex-wrap grid col-3 grid-square padding-lr-15">
						<!-- 列表开始 -->
						<view class="video-list-item" v-for="(item,index) in videoInfo.recommend" @click="goDetail(item.vod_id)" :key="index">
							<view class="bg-img  movie-img image">
								<image :lazy-load="true" style="height: 300upx;" :src="item.vod_pic.search('http') >= 0 ? item.vod_pic : imageUrl + item.vod_pic"></image>
								<view class="text-sm cu-tag bg-blue movie-text-box">
									<text class="text-white movie-text">{{item.vod_score}}</text>
									<text v-if="item.type_id_1 != 1" class="text-white movie-text">{{item.vod_name}}{{item.vod_remarks ? '-' + item.vod_remarks : ''}}</text>
									<text v-else class="text-white movie-text">{{item.vod_name}}</text>
								</view>
							</view>
						</view>
						<!-- 列表结束 -->
					</view>
				</view>
			</view>
			
			<!-- 选集modal -->
			<view class="cu-modal bottom-modal" :class="modalName=='chooseModal'?'show':''">
				<view class="cu-dialog xuanji-box bg-my-gray-cur" :style="xuanjiBoxStyle">
					<view class="cu-bar bg-my-gray">
						<view class="action text-my-white">选集</view>
						<view class="action text-gray" @tap="hideModal"><text class="cuIcon-close"></text></view>
					</view>
					<view class="flex flex-wrap grid col-5 xuanji-item-box" :style="xuanjiItemBoxStyle">
						<view class="padding-10" 
							v-for="(item,index) in videoInfo.srcList[vodPlayFromTab]" 
							:key="index" 
							@click="changeVideo(index)"
						>
							<view class='cu-tag xuanji-item' :class="index == TabCur ? 'bg-my-gray' : 'light bg-my-gray-cur'">
								{{item.name}}
							</view>
						</view> 
					</view>
					<view class="xuanji-bottom">
						
					</view>
				</view>
			</view>
			
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
			
			<!-- 下载modal -->
			<view class="cu-modal bottom-modal" :class="modalName=='downLoadModal'?'show':''">
				<view class="cu-dialog">
					<view class="cu-bar bg-my-gray">
						<view class="action text-blue">下载</view>
						<view class="action text-gray" @tap="hideModal"><text class="cuIcon-close"></text></view>
					</view>
					<view class="flex flex-wrap grid col-3" style="overflow-y: auto;">
						<view style="padding: 30upx;" class="padding-10" v-for="(item,index) in videoInfo.downLoadList" :key="index" @click="downLoadVideo(item.value, index)">
							<view class='cu-tag light bg-grey' style="padding: 30upx;">
								{{item.lable}}
							</view>
						</view> 
					</view>
				</view>
			</view>
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
			<!-- #ifdef MP -->
			<gmy-float-touch :imgLists="imgLists" :mainImg="mainImg" @menuClick="floatTouchClick"></gmy-float-touch>
			<!-- #endif -->
		</view>
	</view>
</template>
<script>
	var getRandomColor = function(){
	  return  '#' +
	    (function(color){
	    return (color +=  '0123456789abcdef'[Math.floor(Math.random()*16)])
	      && (color.length == 6) ?  color : arguments.callee(color);
	  })('');
	}
	// 小程序插屏广告
	let interstitialAd = null;
	// 小程序激励广告
	let rewardedVideoAd = null;
	
	import hex_md5 from '@/common/md5.js';
	import uniPopup from "@/components/uni-popup/uni-popup.vue";
	import uniIcons from '@/components/uni-icon/uni-icon.vue';
	import tvSearch from '@/components/tvSearch/tvSearch.vue';
	import Adview from '@/components/home-ad/ad-view.vue';
	import gmyFloatTouch from "@/components/gmy-float-touch/gmy-float-touch.vue" 
	export default {
		components: {
			uniPopup,
			uniIcons,
			tvSearch,
			Adview,
			gmyFloatTouch
		},
		data() {
			return {
				cssAnimation: false,
				isMp: true,
				mainImg:'/static/gmy-float-touch/main.png',
				imgLists: [
					{
						type: 'share',
						img: '/static/gmy-float-touch/wx.png'
					},
					{
						type: 'image',
						img: '/static/gmy-float-touch/ht.png'
					}
				],
				menuBoxStyle: '',
				isShowxx: true,
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
				statusMaskTop: '',
				scrollLeft: 0,
				TabCur: 0,
				isback: false,
				isLike: false,
				videoInfo: {
					srcList:[{
						name: '',
						url: ''
					}],
					recommend:[],
					title: '',
					playIndex: 0,
					liveMode: false,
					remark: '',
					type: 0,
					currentTime: 0,
					danmuList:[],
					downLoadList:[],
					vod_pic: ''
				},
				vod_id: 0,
				videoType: 0,
				vid: 0,
				videoStyle: '',
				tvMaskBgStyle:'',
				videoUrl: '',
				vodPlayFrom: '',
				vodPlayFromTab: '',
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
				tvMask: false,
				share: null,
				shareTimeLine: null,
				isPlaying: false,
				isPause: false,
				
				//倍速菜单
				rate: "x1",
				//倍速文本,
				animation: null,
				animationMenu: null,
				showRate: true,
				adInfo: uni.getStorageSync('adInfo'),
				watingNum: 0,
				showAdFirst: true,
				sysconf: null,
				showAdHalf: true,
				// 中间看多久弹广告，单位：秒，默认15分钟（900秒）
				halfTimeShowAd: 900,
				dp: null
			}
		},
		onLoad(option) {
			this.vid = option.id;
			if (!this.vid) {
				uni.showModal({
					title: '提示',
					content: '未找到该信息',
					showCancel: false,
					success() {
						uni.navigateBack({
							
						})
					}
				})
				return false;
			}
			if (option.hasOwnProperty('tab')) {
				this.TabCur = option.tab;
			}
			
			// #ifndef H5
			this.videoContext = uni.createVideoContext('myVideo');
			if (this.videoContext != null) {
				if (option.hasOwnProperty('index')) {
					this.TabCur = option.index;
				}
			}
			// #endif
		},
		onShow() {
			if (this.videoInfo) {
				this.currentTime = uni.getStorageSync(hex_md5(this.videoInfo.title + this.TabCur));
			}
		},
		onHide() {
			// #ifndef H5
			this.videoContext.pause();
			// #endif
		}, 
		onUnload() {
			// #ifdef APP-PLUS
				if (!this.isShowxx) {
					// this.tvButtonView.hide();
				}
			// #endif
			this.showAdModel = false;
			let historyList = uni.getStorageSync('historyList'), tmp = [];
			if (historyList.length == 0) {
				historyList = [];
			} else {
				historyList.forEach((v,k) => {
					if (v.id == this.vod_id) {
						historyList[k].index = this.TabCur;
					} else {
						tmp.push(v.id);
					}
				})
			}
			
			if (tmp.length == historyList.length) {
				historyList.unshift({
					img: this.videoInfo.vod_pic,
					title: this.videoInfo.title,
					id: this.vod_id,
					index: this.TabCur
				})
			}
			
			uni.setStorageSync('historyList', historyList);
			uni.$off('tvModelInit');
		},
		onNavigationBarButtonTap(){
			// #ifndef APP-PLUS
				uni.showModal({
					title: '提示',
					content: '该功能只支持APP端',
					showCancel: false
				})
				return false;
			// #endif
			this.modalName = "titleNviewButton";
		},
		mounted() {
			let _this = this;
			_this.getAdinfo();
			uni.$on('showTvMask',function(){
				_this.tvMask = true;
				setTimeout(function(){
					_this.videoContext.pause();
				}, 500)
			})
		},
		methods: {
			onWaiting () {
				
			},
			isSameDay(timeStampA, timeStampB) {
			    let dateA = new Date(timeStampA);
			    let dateB = new Date(timeStampB);
			    return (dateA.setHours(0, 0, 0, 0) == dateB.setHours(0, 0, 0, 0));
			},
			onPlay () {
				let _this = this;
				uni.hideLoading();
				// #ifdef MP-WEIXIN
					let ad_num = uni.getStorageSync('ad_num'),
						now_time = (new Date()).getTime(),
						end = new Date(new Date(new Date().toLocaleDateString()).getTime()+24*60*60*1000-1).getTime();
						ad_num = ad_num ? ad_num : {num: 0, exp_time: now_time}
					// 如果达到设定次数，并且未到重置时间，则不看广告
					if (ad_num.num >= _this.sysconf.play_ad_num && _this.sysconf.play_ad_switch) {
						if (_this.isSameDay(ad_num.exp_time, end)) {
							return true;
						} else {
							ad_num.num = 0;
						}
					}
					
					let timer = uni.getStorageSync(hex_md5(_this.videoInfo.title + _this.TabCur));
					if (
						(_this.sysconf.play_start_ad == 1 && !_this.isShowxx) 
						&& 
						(_this.showAdFirst || (parseInt(timer) == _this.halfTimeShowAd && _this.showAdHalf))
					) {
						_this.videoContext.pause();
						if (parseInt(timer) == _this.halfTimeShowAd) {
							_this.showAdHalf = false;
						}
						uni.showModal({
							title: _this.sysconf.play_ad_title,
							content: _this.sysconf.play_ad_text,
							confirmText: "试试看",
							success(res) {
								_this.showAdFirst = false;
								if (res.confirm) {
									uni.setStorageSync('ad_num', {
										num: parseInt(ad_num.num + 1),
										exp_time: now_time
									});
									rewardedVideoAd.show()
									.catch(() => {
									    rewardedVideoAd.load()
									    .then(() => rewardedVideoAd.show())
									    .catch(err => {
											uni.showToast({
												title: "暂无独享高速线路啦",
												icon: 'none',
												success() {
													_this.videoContext.play();
												}
											})
									    })
									})
								} else {
									// 是否必须看完广告才能播放
									if (_this.sysconf.should_play_end == 1) {
										_this.isShowxx = true;
										_this.videoUrl = '';
										_this.videoContext = null;
										_this.pageInit();
									} else {
										_this.videoContext.play();
									}
								}
							}
						})
					}
				// #endif
			},
			playnext(){
				// 如果是剧集，播放完成自动播放下一集
				if(this.videoInfo.type != 1){
					let index = this.TabCur + 1;
					if (index < this.videoInfo.srcList[this.vodPlayFromTab].length) {
						this.currentTime = 0;
						this.changeVideoInfo(index);
					}
				}
			},
			floatTouchClick(e){
				switch(e) {
					case 1:
						
						break;
					case 2:
						uni.navigateBack({
							
						})
						break;
				}
			},
			getSysConfig(){
				var _this = this;
				_this.$http.post('/index/config/getConfig',{}).then((res)=>{
					uni.setStorageSync('sys_config', res.data);
					_this.sysconf = res.data;
					_this.isShowxx = isNaN(parseInt(_this.sysconf.show_xx)) ? true : parseInt(_this.sysconf.show_xx);
					// 会员等级
					_this.memberGroupLimit(res.data.userinfo);
					// #ifdef MP-WEIXIN
						_this.getWechatMPSetting();
					// #endif
					// #ifndef MP-WEIXIN
						_this.showLoading(_this.isShowxx ? '加载中' : '解析中');
						_this.init();
					// #endif
				})
			},
			getWechatMPSetting(){
				let _this = this;
				_this.showLoading(_this.isShowxx ? '加载中' : '解析中');
				_this.init();
				// uni.getSetting({
				//    success(res) {
				// 	   let set = res.authSetting;
				// 	   let shen = uni.getStorageSync('shen');
				// 	   _this.isShowxx = (!set['scope.userLocation'] || shen) ? true : _this.isShowxx;
				// 	   _this.showLoading(_this.isShowxx ? '加载中' : '解析中');
				// 	   _this.init();
				//    },
				//    fail(res) {
				// 	   _this.showLoading(_this.isShowxx ? '加载中' : '解析中');
				// 	   _this.init();
				//    }
				// })
			},
			showLoading(title){
				uni.showLoading({
					title: title || '加载中',
					mask: true
				})
			},
			//倍速播放动画收起弹出选择倍速
			translateX() {
				this.animation = uni.createAnimation();
				this.animationMenu = uni.createAnimation();
				
				this.animationMenu.translateX(50).step();
				this.animation.translateX(-50).step();
				this.showRate = false;
				this.animationMenu = this.animationMenu.export();
				this.animation = this.animation.export();
			},
			//点击视频屏幕出现倍速选择菜单
			showMenu() {
				var _this = this;
				_this.animation = uni.createAnimation();
				_this.animationMenu = uni.createAnimation();
				
				_this.animationMenu.translateX(-50).step();
				_this.showRate = true;
				_this.animationMenu = _this.animationMenu.export();
				
				//点击Video 4秒不做倍速选择自动消失菜单
				setTimeout(function () {
					if (_this.showRate) {
						_this.hideMenu();
					}
				}, 4000);
			},
			hideMenu(){
				this.animationMenu = uni.createAnimation();
				this.animationMenu.translateX(50).step();
				
				this.animation = uni.createAnimation();
				this.animation.translateX(50).step();
				
				this.showRate = false;
				this.animationMenu = this.animationMenu.export()
				this.animation = this.animation.export()
			},
			//倍速选择
			choseRate(e) {
				let rate = Number(e.target.dataset.rate);
				if (rate == 0) {
					this.playnext();
					return false;
				}
				this.videoContext.playbackRate(rate);
				this.animation = uni.createAnimation();
				this.animation.translateX(50).step();
				this.rate = "x" + rate;
				this.animation = this.animation.export();
			},
			controlstoggle (e) {
				if (e.detail.show) {
					this.showMenu();
				} else {
					this.hideMenu();
				}
			},
			shareBtn(){
				let _this = this;
				// #ifdef APP-PLUS
				// 该功能为测试功能，因目前没有微信开放平台帐号，无法继续完善
				uni.share({
				    provider: "weixin",
				    scene: "WXSceneSession",
				    type: 0,
				    href: _this.$helper.shareUrl,
				    title: _this.videoInfo.title,
				    summary: _this.videoInfo.title,
				    imageUrl: _this.videoInfo.vod_pic,
				    success: function (res) {
				        console.log("success:" + JSON.stringify(res));
				    },
				    fail: function (err) {
				        console.log("fail:" + JSON.stringify(err));
				    }
				});
				// #endif
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
			userLike () {
				var _this = this;
				let userinfo = uni.getStorageSync('userInfo');
				if (!userinfo) {
					uni.showModal({
						content: '请先登录',
						showCancel: false,
						success() {
							uni.navigateTo({
								url: "../user/login?to=1"
							})
						}
					});
					return false;
				}
				_this.$http.post('/videos/videos/addLike',{id: _this.vid}).then((res)=>{
					_this.isLike = true;
					if(res.data.msg.search("取消") > 0){
						_this.isLike = false;
					}
					uni.showToast({
						title: res.data.msg || '添加失败',
						icon: 'none'
					})
				}).catch((err)=>{

				})
			},
			downLoadVideo (url, index) {
				var _this = this;
				let userinfo = uni.getStorageSync('userInfo');
				if (!userinfo) {
					uni.showModal({
						content: '请先登录',
						showCancel: false,
						success() {
							uni.navigateTo({
								url: "../user/login?to=1"
							})
						}
					});
					return false;
				}
				if (!url) {
					uni.showToast({
						title: '错误的下载地址',
						icon: 'none'
					})
					return false;
				}
				_this.$downloader.createDownloadTask({
					downUrl: url,
					saveName: _this.$helper.getUrlName(url)
				}, function(res){
					// _this.hideModal();
					if(0 == res.code){
						uni.showToast({
							title: "已添加进下载列表",
							icon: "none"
						})
						uni.setStorageSync(hex_md5(url), _this.videoInfo);
					} else{
						uni.showToast({
							title: res.msg || "无法下载",
							icon: "none"
						})
					}
				});
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
			sendDanmu (){
				let _this = this;
				let userinfo = uni.getStorageSync('userInfo');
				if (!userinfo) {
					uni.showModal({
						content: '登录信息已失效，请重新登录',
						showCancel: false,
						success() {
							uni.setStorageSync('userInfo', null);
							uni.navigateTo({
								url: "../user/login?to=1"
							})
						}
					});
					return false;
				}
				if (_this.vod_id == 0) {
					uni.showToast({
						title: '视频id获取到',
						icon: 'none'
					})
					return false;
				}
				if (_this.danmuContent.length <= 0) {
					uni.showToast({
						title: '请填写弹幕内容',
						icon: 'none'
					})
					return false;
				}
				let videoCurrentTime = uni.getStorageSync(hex_md5(this.videoInfo.title + _this.TabCur));
				let formValue = {
					vid: _this.vod_id,
					index: _this.TabCur,
					text: _this.danmuContent,
					color: _this.getRandomColor(),
					time: videoCurrentTime
				};
				_this.$http.post('/videos/videos/sendDanme',formValue).then((res)=>{
					if (200 == res.code) {
						_this.videoContext.sendDanmu({
							text: formValue.text,
							color: formValue.color, //'#FEC92F'
						});
						_this.danmuContent = ''; 
					}else{
						_this.danmuContent = '';
						if ('请先登录' == res.data.msg) {
							uni.showModal({
								content: '登录信息已失效，请重新登录',
								showCancel: false,
								success() {
									uni.setStorageSync('userInfo', null);
									uni.navigateTo({
										url: "../user/login?to=1"
									})
								}
							});
							return false;
						}
						uni.showToast({
							title: res.data.msg || '发送失败',
							icon: 'none'
						})
					}
				}).catch((err)=>{

				})
			},
			getRandomColor () {
				let color = [
					"#FFFFFF",
					"#FEC92F",
					"#00CED1",
				];
				return color[Math.floor(Math.random() * color.length)];
				return '#'+Math.floor(Math.random()*16777215).toString(16);
			},
			playing (event) {
				this.isPlaying = true;
				this.isPause = false;
				uni.setStorageSync(hex_md5(this.videoInfo.title + this.TabCur), event.detail.currentTime);
				// 30分钟弹
				if (parseInt(event.detail.currentTime) == this.halfTimeShowAd && this.showAdHalf) {
					this.onPlay();
				}
			},
			waiting(){
				this.isPause = true;
				this.isPlaying = false;
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
			videoErrorCallback (e) {
				
			},
			playerADCancel () {
				let _this = this;
				// 是否显示弹窗广告
				if(_this.sysconf.ad_close == 1) {
					_this.goAd();
				}else{
					_this.$refs['image'].close();
					_this.showAdModel = false;
					_this.drowVideoBox();
				}
			},
			init() {
				var _this = this;
				// 页面初始化
				_this.pageInit()
				// 广告
				_this.showAd();
			},
			memberGroupLimit(userinfo){
				let _this = this;
				// 是否开启了会员等级控制
				if(_this.sysconf.member_group_switch){
					// 如果当前不是审核状态
					if (!_this.isShowxx) {
						// 如果当前登录会员等级低于后台设定等级，则不予播放;如果未登录，直接不给看
						if (!userinfo.hasOwnProperty('group_id') || userinfo.group_id < _this.sysconf.member_group_limit) {
							_this.isShowxx = true;
							_this.pageInit();
						}
					} else {
						// 如果当前已登录，是审核状态，但是会员等级高于设定等级
						if (userinfo.hasOwnProperty('group_id') && userinfo.group_id >= _this.sysconf.member_group_limit) {
							_this.isShowxx = false;
						}
					}
				}
			},
			pageInit(){
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
			},
			goAd () {
				// #ifdef H5
					window.open(this.playerAd.url);
				// #endif
				// #ifdef APP-PLUS
					plus.runtime.openWeb(this.playerAd.url);
				// #endif
				// #ifdef MP
					this.playerADCancel();
				// #endif
			},
		    getAdinfo(){
				let _this = this;
				// 请求服务器，获取弹窗广告和底部
				_this.$http.post('/index/home/getPlayerAdList',{}).then((res)=>{
					uni.setStorageSync('adInfo', res.data);
					_this.adInfo = res.data;
					_this.getSysConfig();
				})
			},
			showAd() {
				let _this = this;
				let sysconf = _this.sysconf;
				// 是否显示弹窗广告
				if (sysconf.player_show_ad == 1) {
					if (_this.adInfo.alert_ad.hasOwnProperty('img')) {
						if (_this.adInfo.alert_ad.ad_type != 5) {
							_this.showAdModel = true;
							_this.playerAd = _this.adInfo.alert_ad;
							_this.$refs['image'].open();
						} else {
							// #ifdef MP-WEIXIN
								// 创建插屏组件
								if (wx.createInterstitialAd) {
									interstitialAd = wx.createInterstitialAd({
										adUnitId: _this.adInfo.alert_ad.url
									})
									if (typeof(interstitialAd) != 'undefined') {
										interstitialAd.onLoad(() => {
											// 15秒内只能弹一次
											setTimeout(function(){
												interstitialAd.show();
											}, 15200);
										})
										interstitialAd.onError((err) => {
											
										})
										interstitialAd.onClose(() => {})
									}
								}
							// #endif
						}
					}
					if (sysconf.player_bottom_ad == 1 && _this.adInfo.alert_public.hasOwnProperty('img')) {
						_this.playerBottomAD = _this.adInfo.alert_public;
						_this.playerBottomAD.img = _this.apiImageUrl + _this.adInfo.alert_public.img;
						_this.playerBottomAD.url = _this.adInfo.alert_public.url;
						_this.playerBottomAD.isshow = 1;
					}
					if (sysconf.show_video_ad == 1 && _this.adInfo.video_ad.hasOwnProperty('img')) {
						// #ifdef MP
							_this.videoAd = _this.adInfo.video_ad.url;
						// #endif
					}
				}
				// 底部广告
				if(sysconf.player_bottom_ad == 1) {
					if (_this.adInfo.alert_public.hasOwnProperty('img')) {
						_this.playerBottomAD = _this.adInfo.alert_public;
						_this.playerBottomAD.img = _this.apiImageUrl + _this.adInfo.alert_public.img;
						_this.playerBottomAD.url = _this.adInfo.alert_public.url;
						_this.playerBottomAD.isshow = 1;
					}else{
						_this.playerBottomAD.isshow = 0;
					}
					if (sysconf.show_video_ad == 1 && _this.adInfo.video_ad.hasOwnProperty('img')) {
						// #ifdef MP
							_this.videoAd = _this.adInfo.video_ad.url;
						// #endif
					}
				}
				// 播放前广告
				if (sysconf.show_video_ad == 1) {
					if (_this.adInfo.video_ad.hasOwnProperty('img')) {
						// #ifdef MP
							_this.videoAd = _this.adInfo.video_ad.url;
						// #endif
					}
				}
				// 激励广告
				if (sysconf.play_start_ad == 1) {
					// #ifdef MP-WEIXIN
						if(wx.createRewardedVideoAd){
							rewardedVideoAd = wx.createRewardedVideoAd({ adUnitId: _this.adInfo.play_start_ad.url });
							if (typeof(rewardedVideoAd) != 'undefined') {
								rewardedVideoAd.onLoad(() => {
									console.log('激励广告加载成功')
								})
								rewardedVideoAd.onError((err) => {
									console.log('激励广告加载失败', err)
								})
								rewardedVideoAd.onClose(res => {
									_this.showAdFirst = false;
									// 用户点击了【关闭广告】按钮
									if (res && res.isEnded) {
										// 正常播放结束，可以下发游戏奖励
										_this.videoContext.play();
									} else {
										if (_this.sysconf.should_play_end == 1) {
											_this.isShowxx = true;
											_this.videoUrl = '';
											_this.videoContext = null;
											_this.pageInit();
										} else {
											_this.videoContext.play();
										}
									}
								})
							}
						}
					// #endif
				}
				// 视频容器
				_this.drowVideoBox();
			},
			drowVideoBox () {
				// #ifdef APP-PLUS
				this.videoStyle = 'position:absolute;background: rgb(0, 0, 0);z-index:1;width:100%;height:'+(this.windowInfo.trueHeight/3.4)+'px;';
				this.tvMaskBgStyle = 'height:'+(this.windowInfo.trueHeight/3.4)+'px;';
				// #endif
				// #ifdef MP
				this.videoStyle = 'position:absolute;background: rgb(0, 0, 0);z-index:1;width:100%;height:'+(this.windowInfo.trueHeight/3)+'px;';
				// #endif
				this.playVideo();
			},
			playVideo() {
				var _this = this;
				// 获取视频信息
				_this.$http.post('/videos/videos/getVideoDetail',{
					vid: _this.vid,
					index: _this.TabCur
				}).then((res)=>{
					uni.hideLoading();
					if (res.code != 200) {
						uni.showModal({
							content: res.data.msg || '未找到此影片信息',
							showCancel: false,
							success() {
								uni.navigateBack()
							}
						});
						return false;
					}
					if (!res.data) {
						uni.showModal({
							content: '未找到此影片信息',
							showCancel: false,
							success() {
								uni.navigateBack()
							}
						});
						return false;
					}
					_this.vod_id = res.data.vid;
					_this.videoInfo.srcList = res.data.srcList;
					_this.videoInfo.recommend = res.data.recommend;
					_this.videoInfo.title = res.data.title;
					_this.videoInfo.vod_pic = res.data.vod_pic;
					_this.videoInfo.remark = res.data.remark;
					_this.videoInfo.type = res.data.type;
					_this.videoInfo.count = res.data.count;
					_this.videoInfo.danmuList = res.data.danmuList;
					// _this.videoInfo.danmuList.unshift(
					// 	{
					// 		text: '请勿相信视频中的任何广告！',
					// 		color: '#ff0000',
					// 		time: 5
					// 	},
					// 	{
					// 		text: '请勿相信视频中的任何广告！',
					// 		color: '#ff0000',
					// 		time: 5
					// 	},
					// 	{
					// 		text: '请勿相信视频中的任何广告！',
					// 		color: '#ff0000',
					// 		time: 5
					// 	}
					// )
					_this.vodPlayFrom = res.data.vod_play_from;
					_this.vodPlayFromTab = _this.vodPlayFrom[0];
					_this.videoInfo.downLoadList = res.data.downLoadList;
					_this.videoInfo.vod_score = res.data.vod_score;
					_this.isLike = res.data.isLike;
					_this.videoUrl = _this.videoInfo.srcList[_this.vodPlayFromTab][0].url;
					_this.currentTime = uni.getStorageSync(hex_md5(_this.videoInfo.title + _this.TabCur));
					// 转发分享
					_this.share = {
						title: _this.videoInfo.title,
						path: '/pages/detail/index?froms=1&id=' + _this.vid + '&tab=' + _this.TabCur,
						imageUrl: _this.videoInfo.vod_pic.search('http') >= 0 ? _this.videoInfo.vod_pic : _this.imageUrl + _this.videoInfo.vod_pic,
						desc: _this.videoInfo.title,
						content: _this.videoInfo.title
					};
					// 朋友圈分享
					_this.shareTimeLine = {
						title: _this.$helper.appName + '邀你来看《' + _this.videoInfo.title + '》',
						query:'id=' + _this.vid + '&tab=' + _this.TabCur + '&froms=2',
						imageUrl: _this.videoInfo.vod_pic.search('http') >= 0 ? _this.videoInfo.vod_pic : _this.imageUrl + _this.videoInfo.vod_pic,
					};
					
					if (!_this.isShowxx) {
						// 设置H5端播放器
						// #ifdef H5
							this.dp = new DPlayer({
							    container: document.getElementById('myVideo'),
								autoplay: true,
							    video: {
							        url: _this.videoUrl,
							        type: 'hls'
							    }
							});
						// #endif
					}
					
					// 设置APP端原生导航栏
					// #ifdef APP-PLUS
						_this.titleNview(res.data.title);
					// #endif
					
					// #ifndef APP-PLUS
						uni.setNavigationBarTitle({
							title: res.data.title
						})
					// #endif
					
					// #ifndef H5
					// 视频信息
					setTimeout(function(){
						_this.changeVideoInfo(_this.TabCur);
						// #ifdef MP-QQ
						_this.videoContext.play();
						// #endif
					}, 1300)
					// #endif
				}).catch((err)=>{
					uni.hideLoading();
				})
			},
			goDetail(id){
				this.vid = id;
				this.TabCur = 0;
				this.playVideo();
			},
			tabSelect(e) {
				this.TabCur = e.currentTarget.dataset.id;
				this.scrollLeft = (e.currentTarget.dataset.id - 1) * 60;
				// #ifndef H5
				this.videoContext.stop();
				// #endif
				this.currentTime = 0;
				this.changeVideoInfo(e.currentTarget.dataset.id)
			},
			tabPlayFromSelect(e) {
				this.vodPlayFromTab = e;
				this.TabCur = -1;
				this.cssAnimation = true;
				let _this = this;
				uni.showLoading({
					title: '加载中...',
					mask: true,
					success() {
						uni.hideLoading();
						setTimeout(function(){
							_this.cssAnimation = false;
						}, 800)
					}
				})
			},
			showModal(e) {
				if (e.currentTarget.dataset.target =="downLoadModal" && this.videoInfo.downLoadList.length == 0) {
					uni.showToast({
						title: "此影片不可下载",
						icon: "none"
					})
					return false;
				}
				this.modalName = e.currentTarget.dataset.target;
			},
			hideModal() {
				this.modalName = null
			},
			changeVideo(index) {
				this.changeVideoInfo(index)
			},
			changeVideoInfo(index){
				var _this = this;
				uni.showLoading({
					title: _this.isShowxx ? '加载中...' : '解析中',
					mask: true
				})
				_this.TabCur = index;
				_this.showAdFirst = true;
				// 转发分享
				_this.share.path = '/pages/detail/index?froms=1&id=' + _this.vid + '&tab=' + _this.TabCur;
				// 获取弹幕
				_this.$http.post('/videos/videos/getDanmuList',{
					vid: _this.vid,
					index: _this.TabCur
				}).then((res)=>{
					uni.hideLoading();
					if (200 == res.code) {
						// #ifndef H5
							_this.videoInfo.danmuList = res.data;
							_this.videoUrl = _this.videoInfo.srcList[_this.vodPlayFromTab][index].url;
							_this.videoContext.play();
						// #endif
						// #ifdef H5
							_this.dp.switchVideo(
								{
									url: _this.videoInfo.srcList[_this.vodPlayFromTab][index].url,
								}
							);
							_this.dp.play();
						// #endif
					}
				})
			},
			listenFullScreen(event) {
				if (event.detail.fullScreen) {
					
				}
			},
			drawBackBtn () {
				var _this = this;
				// 画TV
				_this.tvButtonView = new plus.nativeObj.View('goBackBtn',
						{
							top: _this.windowInfo.statusBarHeight + 15 + 'px',
							left: _this.windowInfo.windowWidth - 50 + 'px',
							height:'35px',
							width:'35px',
							opacity: 0.5
						},
						[
							{
								id:'img',
								tag:'img',
								src:'/static/tv.png',
								position:{
									top:'7.2px',
									left:'5px',
									width:'25px',
									height:'20px',
								},
							},
						]
					);
				// 显示按钮
				_this.tvButtonView.show();
				_this.tvButtonView.addEventListener("click", function(){
					_this.sendtv()
				})
			}
		}
	}
</script>

<style>
	.page{
		min-height: 100vh;
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
		height: 25px!important;
		line-height: 25px!important;
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
	  width: 120rpx;
	  /* height: 60rpx; */
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
	  right:-110rpx;
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
	  width: 100rpx;
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
	
	.right-animation{
		-webkit-animation:fadeInRightBig .3s .2s ease both;
		-moz-animation:fadeInRightBig .3s .2s ease both;
	}
	@-webkit-keyframes fadeInRightBig{
		0%{opacity:0;
		-webkit-transform:translateX(1000px)}
		100%{opacity:1;
		-webkit-transform:translateX(0)}
	}
	@-moz-keyframes fadeInRightBig{
		0%{opacity:0;
		-moz-transform:translateX(1000px)}
		100%{opacity:1;
		-moz-transform:translateX(0)}
	}
</style>