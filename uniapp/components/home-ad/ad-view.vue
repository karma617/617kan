<template>
	<view>
		<view class="cu-card case no-card margin-top margin-bottom ad-box" v-if="adSwitchKg && homeADList && homeADList.ad_type < 3" @click="goaddetail()">
			<view class="cu-item shadow">
				<view class="image">
					<image v-if="homeADList.ad_type == 1" style="max-height: 440upx;" :src="homeADList.img ? apiImageUrl + homeADList.img : '/static/ad.png'"
					 mode="widthFix"></image> 
					<video id="adVideo" :src="homeADList.v_url" style="max-height: 440upx;width: 100%;" :autoplay="true" loop muted :show-play-btn="false" :controls="false" objectFit="fill" v-if="homeADList.ad_type == 2"></video>
					<view class="cu-bar bg-shadeBottom"> <text class="text-cut">{{homeADList.desc ? homeADList.desc : ''}}</text></view>
				</view>
			</view> 
			<view class="cu-list menu-avatar">
				<view class="cu-item">
					<view class="cu-avatar round lg" :style="[{ backgroundImage:'url(' + (homeADList.img ? apiImageUrl + homeADList.img : '/static/ad.png') + ')' }]"></view>
					<view class="content flex-sub">
						<view class="text-grey">{{homeADList.title ? homeADList.title : '广告'}}</view>
						<view class="text-gray text-sm flex justify-between">
							{{num.days}} 天前
							<view class="text-gray text-sm">
								<text class="cuIcon-attentionfill margin-lr-xs"></text> {{num.wacth}}
								<text class="cuIcon-appreciatefill margin-lr-xs"></text> {{num.good}}
								<text class="cuIcon-messagefill margin-lr-xs"></text> {{num.feedback}}
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="cu-card case no-card ad-box" v-if="adSwitchKg && homeADList && homeADList.ad_type >= 3">
			<!-- #ifdef MP-WEIXIN -->
				<!-- 小程序banner广告 -->
				<ad v-if="homeADList.ad_type == 3" :style="pageMpadStyle" @load="pageMpadLoad()" :unit-id="homeADList.url" ad-intervals="35"></ad>
				<!-- 小程序视频广告 -->
				<ad v-if="homeADList.ad_type == 4" ad-type="video" :style="pageMpadStyle" @load="pageMpadLoad()" :unit-id="homeADList.url" ad-intervals="35"></ad>
				<!-- 小程序格子广告 -->
				<ad v-if="homeADList.ad_type == 6" ad-type="grid" :style="pageMpadStyle" @load="pageMpadLoad()" :unit-id="homeADList.url" ad-intervals="35" grid-opacity="0.8" grid-count="5" ad-theme="white"></ad>
			<!-- #endif -->
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			adSwitchKg: Number,
			homeADList: Object
		},
		data() {
			return {
				num:{
					days: 1,
					wacth: 1,
					good: 1,
					feedback: 1
				},
				imageUrl: this.$helper.imageUrl,
				apiImageUrl: this.$helper.apiImageUrl,
				videoContext: null,
				pageMpadStyle: '',
				adInfo: null
			};
		},
		mounted() {
			this.init();
		},
		methods:{
			pageMpadLoad() {
				let _this = this;
				setTimeout(function(){
					_this.pageMpadStyle = 'width: 100%!important;';
				}, 1500);
			},
			play () {
				// #ifndef H5
					this.videoContext = uni.createVideoContext('adVideo');
					if (this.videoContext != null) {
						this.videoContext.play();
					}
				// #endif
			},
			init() {
				this.num.days = this.randomNum(1, 31);
				this.num.wacth = this.randomNum(0, 100);
				this.num.good = this.randomNum(0, 100);
				this.num.feedback = this.randomNum(0, 100);
			},
			goaddetail () {
				// #ifdef H5
					window.open(this.homeADList.url);
				// #endif
				// #ifndef H5
					plus.runtime.openWeb(this.homeADList.url);
				// #endif
			},
			randomNum(minNum,maxNum){ 
				return parseInt(Math.random() * (maxNum - minNum + 1 ) + minNum, 10);
			},
		}
	}
</script>

<style>
	.ad-box {
		width: 100vw;
	}
</style>
