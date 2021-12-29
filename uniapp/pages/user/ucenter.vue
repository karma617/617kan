<template>
	<view class="pages">
		<view class="cu-bar bg-my-gray solid-bottom" @click="changeUsi">
			<view class="action fl nav-arrow">
				<text class="cuIcon-form text-grey"></text> 资料修改
			</view>
			<view class="fr">
				<text class="text-grey text-sm video-more-text"></text>
			</view>
		</view>
		<view class="cu-bar bg-my-gray solid-bottom" @click="changePsw">
			<view class="action fl nav-arrow">
				<text class="cuIcon-safe text-grey"></text> 修改密码
			</view>
			<view class="fr">
				<text class="text-grey text-sm video-more-text"></text>
			</view>
		</view>
		<view class="cu-bar bg-my-gray solid-bottom" @click="giftCode">
			<view class="action fl nav-arrow">
				<text class="cuIcon-ticket text-grey"></text> 兑换码
			</view>
			<view class="fr">
				<text class="text-grey text-sm video-more-text"></text>
			</view>
		</view>
		<!-- 广告 -->
		<Adview :adSwitchKg="adSwitchKg" :homeADList="homeADList"></Adview>
	</view>
</template>

<script>
	import Adview from '@/components/home-ad/ad-view.vue';
	export default {
		components: {
			Adview
		},
		data() {
			return {
				adSwitchKg: 0,
				homeADList: null,
				isShowxx: 0,
			}
		},
		onShow() {
			let config = uni.getStorageSync('sys_config');
			this.isShowxx = isNaN(parseInt(config.show_xx)) ? true : parseInt(config.show_xx);
		},
		onReady() {
			let config = uni.getStorageSync('sys_config');
			this.adSwitchKg = isNaN(parseInt(config.ad_switch)) ? 0 : parseInt(config.ad_switch);
			if (!this.homeADList && this.adSwitchKg) {
				let adList = uni.getStorageSync('home_ad');
				if (adList.length > 0) {
					let num = parseInt(Math.random() * (adList.length - 1),10);
					this.homeADList = adList[num];
				}
			}
		},
		methods: {
			changeUsi () {
				uni.navigateTo({
					url: '/pages/user/child/changeUsi'
				})
			},
			changePsw () {
				uni.navigateTo({
					url: '/pages/user/child/changePsw'
				})
			},
			giftCode () {
				uni.navigateTo({
					url: '/pages/user/child/giftCode'
				})
			},
		},
	}
</script>

<style>
</style>
