<template>
    <view class="guide" :style="guideStyle">
        <swiper class="flex1" interval="3000" :show-indicators="false" :auto-play="autoPlay" @change="sliderChange" :infinite="false" :forbid-slide-animation="false">
            <swiper-item class="flex1" v-for="(img, index) in imageList" :key="index">
                <view class="flex1" @click="clickAd(img.url)">
                    <image class="flex1" resize="contain" :src="apiImageUrl + img.img" :style="guideStyle"/>
                </view>
            </swiper-item>
        </swiper>
        <view class="swiper-to-home" @click="launchApp()"><text class="swiper-to-home-text">跳过</text></view>
    </view>
</template>

<script>
const SystemInfo = uni.getSystemInfoSync();

export default {
    data() {
        return {
			index: 0,
			imageUrl: this.$helper.imageUrl,
			apiImageUrl: this.$helper.apiImageUrl,
			guideStyle: '',
            imageList: [],
            autoPlay: false,
            currIndex: 0,
            screenWidth: SystemInfo.screenWidth,
			showAd: true
        };
    },
	created() {
		// 自绘高度
		var _this = this;
		const res = uni.getSystemInfoSync();
		let statusBarHeight = res.statusBarHeight;
		let windowHeight = res.windowHeight;
		let windowWidth = res.windowWidth;
		let trueHeight = windowHeight - statusBarHeight;
		_this.guideStyle = 'min-height:' + windowHeight + 'px; height:' + windowHeight + 'px; width: ' + windowWidth + 'px;';
	},
	mounted() {
		this.getAdimg();
	},
    methods: {
		getAdimg () {
			var _this = this;
			_this.$http.post('/index/home/getGuideImg',{}).then((res)=>{
				_this.imageList = res.data.list;
			}).catch((err)=>{

			})
		},
        sliderChange(e) {
            this.currIndex = e.detail.current;
        },
        clickAd(url) {
			// #ifdef H5
				window.open(url);
			// #endif
			// #ifdef APP-PLUS
				plus.runtime.openWeb(url);
			// #endif
			// #ifdef MP-WEIXIN
				uni.navigateTo({
					url: '/pages/webview/webview?url=' + encodeURIComponent(url)
				});
			// #endif
			this.showAd = false;
        },
		launchAppIndex() {
            if (this.imageList.length == this.currIndex + 1) {
                // this.launchApp();
				uni.switchTab({
				    url: '/pages/index/index'
				});
            } else {
                return;
            }
        },
        launchApp() {
			let _this = this;
			// 是否显示弹窗广告
			let sysconf = uni.getStorageSync('sys_config');
			if(sysconf.ad_close == 1) {
				let url = _this.imageList[_this.index].url;
				_this.clickAd(url);
			}else{
				uni.switchTab({
				    url: '/pages/index/index'
				});
			}
        }
    },
	onShow() {
		if(!this.showAd){
			uni.switchTab({
			    url: '/pages/index/index'
			});
		}
	}
};
</script>
<style scoped>
.guide {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.flex1 {
    flex: 1;
}
.apptestnnnn {
    border-width: 1px;
    border-color: #4cd964;
    border-style: solid;
}
.apptest {
    border-width: 1px;
    border-color: #007aff;
    border-style: solid;
}
.swiper-to-home {
    position: absolute;
    z-index: 999;
    right: 40rpx;
    /* #ifndef MP */
    top: 80rpx;
    /* #endif */
    /* #ifdef MP */
    top: 150rpx;
    /* #endif */
}

.swiper-to-home-text {
    color: #ffffff;
    text-align: center;
    background-color: rgba(0, 0, 0, 0.5);
    border-width: 1rpx;
    border-color: #ffffff;
    border-style: solid;
    border-radius: 30rpx;
    font-size: 28rpx;
    padding-top: 5rpx;
    padding-bottom: 5rpx;
    padding-left: 25rpx;
    padding-right: 25rpx;
}

.indicator {
    width: 714rpx;
    height: 30rpx;
    position: absolute;
    bottom: 50rpx;
    left: 1rpx;
    item-color: #f6f6f6;
    item-selected-color: #fd575c;
    item-size: 20rpx;
}
</style>
