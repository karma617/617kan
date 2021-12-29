<template>
	<view class="pages">
		<u-notice-bar v-if="msg.length > 0" mode="vertical" color="#ed2f85" bg-color="#161827" :no-list-hidden="false" :list="msg" @click="showGonggao"></u-notice-bar>
		<view class="bg-my-black" style="height: 12rem;">
			<swiper
				class="square-dot"
				:indicator-dots="true"
				:circular="true"
				:autoplay="true"
				interval="5000"
				duration="500"
				@change="cardSwiper"
				indicator-color="#a1a6b3"
				indicator-active-color="#ed2f85"
				style="height: 12rem;"
			>
				<block v-for="(item, index) in swiperList" :key="index" :class="cardCur == index ? 'cur' : ''">
					<swiper-item @click="goDetail(item, true)" style="height: 12rem;">
						<view style="height: 12rem;" class="swiper-item" v-if="item.hasOwnProperty('type') && item.type == 'image'">
							<image :lazy-load="true" :src="item.img"></image>
						</view>
						<!-- #ifdef MP-WEIXIN -->
						<view style="height: 12rem;" class="swiper-item ad-swiper-item" v-if="item.hasOwnProperty('type') && item.type == 'mpad'">
							<ad :style="mpadStyle" @load="mpadLoad" :unit-id="item.ad_url" ad-intervals="35"></ad>
						</view>
						<!-- #endif -->
					</swiper-item>
				</block>
			</swiper>
		</view>
		<view class="list-content-box">
			<view class="list-content-box-re">
				<view class="cu-bar bg-my-black" @click="goCateDetail(1)">
					<view class="action fl nav-arrow">
						<text class="cuIcon-hot text-my-red"></text>
						热门推荐
					</view>
					<view class="fr">
						<text class="text-my-white text-sm video-more-text">更多</text>
					</view>
				</view>
				<view class="bg-my-black padding-25">
					<view class="flex flex-wrap grid col-3 grid-square gundong-box">
						<view class="movie-box video-list-item" v-for="(item, index) in hotVideos.list" @click="goDetail(item.vod_id)" :key="index">
							<view class="bg-img  movie-img image">
								<image :lazy-load="true" style="height: 300upx;" :src="item.vod_pic.search('http') >= 0 ? item.vod_pic : imageUrl + item.vod_pic"></image>
								<view class="text-sm cu-tag bg-blue movie-text-box">
									<text class="text-white movie-text">{{ item.vod_score }}</text>
									<text v-if="item.type_id_1 != 1" class="text-white movie-text">{{ item.vod_name }}</text>
									<text v-else class="text-white movie-text">{{ item.vod_name }}</text>
								</view>
							</view>
						</view>
					</view>
				</view>
				<!-- 广告 -->
				<Adview :adSwitchKg="adSwitchKg" :homeADList="homeADList[0]" v-if="homeADList[0]"></Adview>

				<view class="cu-bar bg-my-black" @click="goCateDetail(2)">
					<view class="action fl nav-arrow">
						<text class="cuIcon-hot text-my-red"></text>
						热播剧
					</view>
					<view class="fr">
						<text class="text-my-white text-sm video-more-text">更多</text>
					</view>
				</view>
				<view class="bg-my-black padding-25 ">
					<view class="flex flex-wrap grid col-3 grid-square gundong-box">
						<view class="movie-box video-list-item" v-for="(item, index) in ihotVideos.list" :key="index" @click="goDetail(item.vod_id)">
							<view class="bg-img  movie-img image">
								<image :lazy-load="true" style="height: 300upx;" :src="item.vod_pic.search('http') >= 0 ? item.vod_pic : imageUrl + item.vod_pic"></image>
								<view class="text-sm cu-tag bg-blue movie-text-box">
									<text class="text-white movie-text">{{ item.vod_score }}</text>
									<text v-if="item.type_id_1 != 1" class="text-white movie-text">{{ item.vod_name }}</text>
									<text v-else class="text-white movie-text">{{ item.vod_name }}</text>
								</view>
							</view>
						</view>
					</view>
				</view>
				<!-- 广告 -->
				<Adview :adSwitchKg="adSwitchKg" :homeADList="homeADList[1]" v-if="homeADList[1]"></Adview>

				<view class="cu-bar bg-my-black" @click="goCateDetail(3)">
					<view class="action fl nav-arrow">
						<text class="cuIcon-coin text-my-red"></text>
						二次元
					</view>
					<view class="fr">
						<text class="text-my-white text-sm video-more-text">更多</text>
					</view>
				</view>
				<view class="bg-my-black padding-25 ">
					<view class="flex flex-wrap grid col-3 grid-square gundong-box">
						<view class="movie-box video-list-item" v-for="(item, index) in acgViedeos.list" :key="index" @click="goDetail(item.vod_id)">
							<view class="bg-img  movie-img image">
								<image :lazy-load="true" style="height: 300upx;" :src="item.vod_pic.search('http') >= 0 ? item.vod_pic : imageUrl + item.vod_pic"></image>
								<view class="text-sm cu-tag bg-blue movie-text-box">
									<text class="text-white movie-text">{{ item.vod_score }}</text>
									<text v-if="item.type_id_1 != 1" class="text-white movie-text">{{ item.vod_name }}</text>
									<text v-else class="text-white movie-text">{{ item.vod_name }}</text>
								</view>
							</view>
						</view>
					</view>
				</view>
				<!-- 广告 -->
				<Adview :adSwitchKg="adSwitchKg" :homeADList="homeADList[2]" v-if="homeADList[2]"></Adview>
				<view class="cu-bar bg-my-black" @click="goCateDetail(4)">
					<view class="action fl nav-arrow">
						<text class="cuIcon-record text-my-red"></text>
						综艺
					</view>
					<view class="fr">
						<text class="text-my-white text-sm video-more-text">更多</text>
					</view>
				</view>
				<view class="bg-my-black padding-25 ">
					<view class="flex flex-wrap grid col-3 grid-square gundong-box">
						<view class="movie-box video-list-item" v-for="(item, index) in varietyViedeos.list" :key="index" @click="goDetail(item.vod_id)">
							<view class="bg-img  movie-img image">
								<image :lazy-load="true" style="height: 300upx;" :src="item.vod_pic.search('http') >= 0 ? item.vod_pic : imageUrl + item.vod_pic"></image>
								<view class="text-sm cu-tag bg-blue movie-text-box">
									<text class="text-white movie-text">{{ item.vod_score }}</text>
									<text v-if="item.type_id_1 != 1" class="text-white movie-text">{{ item.vod_name }}</text>
									<text v-else class="text-white movie-text">{{ item.vod_name }}</text>
								</view>
							</view>
						</view>
					</view>
				</view>
				<!-- 广告 -->
				<Adview :adSwitchKg="adSwitchKg" :homeADList="homeADList[3]" v-if="homeADList[3]"></Adview>
			</view>
		</view>
		<!-- 首页弹出层 -->
		<uni-popup ref="popup" type="center" :popupStyle="popupStyle">
			<view class="popup-imgbox">
				<view><image :show-menu-by-longpress="true" :src="popup.popup_img"></image></view>
				<view>
					<text>{{ popup.popup_title }}</text>
				</view>
			</view>
		</uni-popup>
		<!-- #ifdef MP -->
		<add-tip :tip="tip" :duration="duration" />
		<!-- #endif -->
	</view>
</template>

<script>
import addTip from '@/components/struggler-uniapp-add-tip/struggler-uniapp-add-tip.vue';
import Adview from '@/components/home-ad/ad-view.vue';
import uNoticeBar from '@/components/u-notice-bar/u-notice-bar.vue';
import autoUpdater from '@/common/autoUpdater.min.js';
import uniPopup from '@/components/uni-popup/uni-popup.vue';
export default {
	components: {
		Adview,
		uNoticeBar,
		uniPopup,
		addTip
	},
	data() {
		return {
			tip: '点击「添加到我的小程序」，下次访问更便捷',
			duration: 5,
			mpadStyle: '',
			adSwitchKg: 0,
			homeADList: [],
			keyworld: '',
			hotVideos: [],
			ihotVideos: [],
			acgViedeos: [],
			varietyViedeos: [],
			cardCur: 0,
			swiperList: [],
			imageUrl: this.$helper.imageUrl,
			apiImageUrl: this.$helper.apiImageUrl,
			videoList: false,
			bannerList: false,
			msg: [],
			msgList: [],
			shareTimeLine: null,
			popupStyle: 'background-color:#161827; color: #dae1f2; border-radius: 10px; padding: 10px',
			popup: {
				popup_img: '',
				popup_title: ''
			}
		};
	},
	onLoad() {
		// 朋友圈分享
		this.shareTimeLine = {
			title: this.$helper.appName + '，海量影视，一起来看！'
		};
		this.initPage();
	},
	onPullDownRefresh() {
		uni.removeStorageSync('localVideoInfo');
		this.initPage();
	},
	onTabItemTap(e) {
		// if (0 == e.index) {
		// 	this.initPage();
		// }
	},
	methods: {
		mpadLoad() {
			let _this = this;
			setTimeout(function() {
				_this.mpadStyle = 'width: 100%!important; height:155px!important';
			}, 800);
		},
		checkUpdate() {
			var _this = this;
			_this.$http
				.post('/index/config/updateInfo', {})
				.then(res => {
					if (res.code == 200) {
						let versionInfo = res.data;
						let a_ver = null;
						switch (_this.$os) {
							case 'android':
								a_ver = versionInfo.android;
								break;
							case 'ios':
								a_ver = versionInfo.ios;
								break;
						}
						let ver = plus.runtime.version.split('.').join('');
						if (a_ver.version.split('.').join('') > ver && a_ver.atOnce == true) {
							autoUpdater.init({
								packageUrl: a_ver.download_url,
								content: a_ver.log,
								contentAlign: 'left',
								cancel: '取消该版本',
								cancelColor: '#007fff'
							});
							autoUpdater.show();
						}
					}
				})
		},
		getHomeADList() {
			var _this = this;
			_this.$http
				.post('/index/home/getHomeAdList', {})
				.then(res => {
					let data = res.data;
					if (data.length > 0 || data.hasOwnProperty('list')) {
						if (data.list.length > 0) {
							// #ifndef MP-WEIXIN
							let tmp = [];
							data.list.forEach((v, k) => {
								if (v.ad_type < 3) {
									tmp.push(data[k]);
								}
							});
							_this.homeADList = tmp;
							// #endif
							// #ifdef MP-WEIXIN
							uni.setStorageSync('home_ad', data.list);
							_this.homeADList = data.list;
							// #endif
						}
					}
				})
		},
		goad(index) {
			// #ifdef H5
			window.open();
			// #endif
			// #ifndef H5
			plus.runtime.openWeb();
			// #endif
		},
		adSwitch() {
			let config = uni.getStorageSync('sys_config');
			this.adSwitchKg = isNaN(parseInt(config.ad_switch)) ? 0 : parseInt(config.ad_switch);
		},
		showGonggao(e) {
			let msgInfo = this.msgList[e];
			uni.showModal({
				title: msgInfo.title,
				content: msgInfo.content,
				showCancel: false
			});
		},
		TowerSwiper(name) {
			let list = this[name];
			for (let i = 0; i < list.length; i++) {
				list[i].zIndex = parseInt(list.length / 2) + 1 - Math.abs(i - parseInt(list.length / 2));
				list[i].mLeft = i - parseInt(list.length / 2);
			}
			this.swiperList = list;
		},
		cardSwiper(e) {
			this.cardCur = e.detail.current;
		},
		goCateDetail(type) {
			switch (type) {
				case 1:
					uni.navigateTo({ url: '../cate/lists?type=1' });
					break;
				case 2:
					uni.navigateTo({ url: '../cate/lists?type=2' });
					break;
				case 3:
					uni.navigateTo({ url: '../cate/lists?type=3' });
					break;
				case 4:
					uni.navigateTo({ url: '../cate/lists?type=4' });
					break;
				default:
					return false;
			}
		},
		goDetail(item, banner = false) {
			uni.setStorageSync('goback', '/pages/index/index');
			if (banner) {
				if (item.isad == 1) {
					// #ifdef APP-PLUS
					plus.runtime.openWeb(item.ad_url);
					// #endif
				} else {
					if (parseInt(item.vid) > 0) {
						uni.navigateTo({ url: '../detail/index?id=' + item.vid });
					}
				}
			} else {
				if (parseInt(item) > 0) {
					uni.navigateTo({ url: '../detail/index?id=' + item });
				}
			}
		},
		search() {
			uni.navigateTo({ url: '../search/index' });
		},
		getVideoList() {
			let _this = this;
			let localVideoInfo = uni.getStorageSync('localVideoInfo');
			let now_timestamp = Date.parse(new Date()) / 1000;
			// 如果本地存有缓存并且没过期（10分钟内不再重新获取）
			if (localVideoInfo && now_timestamp < localVideoInfo.exp_time) {
				_this.hotVideos = localVideoInfo.hotVideos;
				_this.ihotVideos = localVideoInfo.ihotVideos;
				_this.acgViedeos = localVideoInfo.acgViedeos;
				_this.varietyViedeos = localVideoInfo.varietyViedeos;
				_this.videoList = localVideoInfo.videoList;
				return true;
			}

			_this.$http
				.post('/videos/videos/getHomeVideoLists', {})
				.then(res => {
					uni.hideLoading();
					_this.hotVideos = res.data.movie;
					_this.ihotVideos = res.data.tv;
					_this.acgViedeos = res.data.acg;
					_this.varietyViedeos = res.data.variety;
					_this.videoList = true;
					localVideoInfo = {
						exp_time: Date.parse(new Date()) / 1000 + 600,
						hotVideos: _this.hotVideos,
						ihotVideos: _this.ihotVideos,
						acgViedeos: _this.acgViedeos,
						varietyViedeos: _this.varietyViedeos,
						videoList: _this.videoList
					};
					uni.setStorageSync('localVideoInfo', localVideoInfo);
				})
		},
		getbannerList() {
			var _this = this;
			_this.$http
				.post('/banner/banner/getBannerLists', {})
				.then(res => {
					uni.hideLoading();
					let data = res.data.list;
					// #ifndef MP-WEIXIN
					let tmp = [];
					data.forEach((v, k) => {
						data[k].img = v.img.search('http') >= 0 ? v.img : _this.apiImageUrl + v.img;
						if (v.isad < 2) {
							tmp.push(data[k]);
						}
					});
					_this.swiperList = tmp;
					// 初始化towerSwiper 传已有的数组名即可
					_this.TowerSwiper('swiperList');
					_this.bannerList = true;
					// #endif
					// #ifdef MP-WEIXIN
					data.forEach((v, k) => {
						data[k].img = v.img.search('http') >= 0 ? v.img : _this.apiImageUrl + v.img;
					});
					_this.swiperList = data;
					// 初始化towerSwiper 传已有的数组名即可
					_this.TowerSwiper('swiperList');
					_this.bannerList = true;
					// #endif
				})
		},
		getMsgList() {
			var _this = this;
			_this.$http
				.post('/index/home/getMsgLists', {})
				.then(res => {
					_this.msgList = res.data.list;
					if (_this.msgList.length > 0) {
						let msg = [];
						_this.msgList.forEach((v, k) => {
							msg.push(v.title);
						});
						_this.msg = msg;
					}
				})
		},
		getPopupInfo() {
			let config = uni.getStorageSync('sys_config');
			let showPopup = isNaN(parseInt(config.show_popup)) ? 0 : parseInt(config.show_popup);
			if (showPopup) {
				this.popup.popup_img = config.popup_img.search('http') >= 0 ? config.popup_img : this.$helper.apiImageUrl + config.popup_img;
				this.popup.popup_title = config.popup_title;
				this.$refs.popup.open();
			}
		},
		initPage() {
			// #ifdef APP-PLUS
			this.checkUpdate();
			// #endif
			this.hotVideos = '';
			this.ihotVideos = '';
			this.acgViedeos = '';
			this.varietyViedeos = '';
			this.swiperList = '';
			uni.showLoading({
				title: '加载中...',
				mask: true
			});
			this.getHomeADList();
			this.adSwitch();
			// #ifdef MP
			this.getPopupInfo();
			// #endif
			this.getMsgList();
			this.getbannerList();
			this.getVideoList();
			uni.stopPullDownRefresh();
		}
	}
};
</script>

<style>
.pages {
	overflow: hidden;
}

.item {
	background: #fff;
	width: fit-content;
	padding: 20px;
	margin: 20px;
	box-shadow: 0 1px 6px rgba(0, 0, 0, 0.2);
	width: 100%;
	min-height: 300px;
}
/* swiper msg */
.uni-swiper-msg {
	width: 97%;
	padding: 12rpx 0rpx;
	margin: 0 auto;
	flex-wrap: nowrap;
	display: flex;
}
.uni-swiper-msg-icon {
	width: 50upx;
	margin-right: 20upx;
}
.uni-swiper-msg-icon image {
	width: 100%;
	flex-shrink: 0;
}
.uni-swiper-msg swiper {
	width: 100%;
	height: 50upx;
}
.uni-swiper-msg swiper-item {
	line-height: 50upx;
}
.popup-imgbox {
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	align-items: center;
}
.popup-imgbox image {
	width: 265px;
	height: 265px;
	margin-bottom: 10px;
}
.list-content-box {
	width: 100%;
}
.list-content-box-re {
	position: relative;
	overflow-y: auto;
	background-color: #3e485f;
}
</style>
