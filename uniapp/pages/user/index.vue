<template>
	<view class="center pages">
		<view class="logo" @click="goLogin" :hover-class="!login ? 'logo-hover' : ''">
			<image class="logo-img" :src="userInfo ? userInfo.avatar : avatarUrl"></image>
			<view class="logo-title">
				<view class="username-box" v-if="login">
					<text class="uer-name">Hi，{{userInfo.nick ? userInfo.nick : userInfo.mobile}}</text>
					<view class="uer-group">
						<text>{{userInfo.group_name}}</text>
						<text v-if="userInfo.expire_time != -1">到期时间：{{userInfo.expire_time}}</text>
					</view>
				</view>
				<view v-else>
					<text class="uer-name">去登录</text>
				</view>
				<text class="go-login navigat-arrow">&#xe65e;</text>
			</view>
		</view>
		<view class="cu-bar bg-my-gray solid-bottom" @click="ucenter">
			<view class="action fl nav-arrow">
				<text class="cuIcon-people text-grey"></text> 账号管理
			</view>
		</view>
		<view class="cu-bar bg-my-gray solid-bottom" @click="history">
			<view class="action fl nav-arrow">
				<text class="cuIcon-appreciate text-grey"></text> 浏览历史
			</view>
		</view>
		<!-- #ifdef APP-PLUS -->
			<view class="cu-bar bg-my-gray solid-bottom" @click="download" v-if="!isIos">
				<view class="action fl nav-arrow">
					<text class="cuIcon-down text-grey"></text> 我的下载
				</view>
				
			</view>
			<view class="cu-bar bg-my-gray solid-bottom" @click="vip">
				<view class="action fl nav-arrow">
					<text class="cuIcon-evaluate text-grey"></text> 无水印短视频下载
				</view>
				
			</view>
			<view class="cu-bar bg-my-gray solid-bottom" @click="punch">
				<view class="action fl nav-arrow">
					<text class="cuIcon-punch text-grey"></text> 免责声明
				</view>
				
			</view>
			<view class="cu-bar bg-my-gray solid-bottom" @click="about">
				<view class="action fl nav-arrow">
					<text class="cuIcon-info text-grey"></text> 关于应用
				</view>
				
			</view>
		<!-- #endif -->
		<view class="cu-bar bg-my-gray solid-bottom margin-top" @click="feedback">
			<view class="action fl nav-arrow">
				<text class="cuIcon-mark text-grey"></text> 帮助与反馈
			</view>
			
		</view>
		<view class="cu-bar bg-my-gray solid-bottom" @click="setting">
			<view class="action fl nav-arrow">
				<text class="cuIcon-settings text-grey"></text> 设置
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
				login: false,
				avatarUrl: "../../static/avatar.png",
				userInfo: null,
				imageUrl: this.$helper.imageUrl,
				apiImageUrl: this.$helper.apiImageUrl,
				isIos: this.$os == "ios",
				isShowxx: 0,
				adSwitchKg: 0,
				homeADList: null
			}
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
		onPullDownRefresh() {
			this.init();
		},
		methods: {
			init() {
				var _this = this;
				_this.$http.post('/index/config/getConfig',{}).then((res)=>{
					uni.stopPullDownRefresh();
					uni.setStorageSync('sys_config', res.data);
					_this.isShowxx = isNaN(parseInt(res.data.show_xx)) ? true : parseInt(res.data.show_xx);
					uni.setStorageSync('userInfo', res.data.userinfo);
					if (res.data.userinfo.hasOwnProperty('mobile')) {
						this.login = true;
						this.userInfo = res.data.userinfo;
						this.userInfo.avatar = this.userInfo.avatar.search('http') >= 0 ? this.userInfo.avatar : this.apiImageUrl + this.userInfo.avatar;
					}else{
						this.login = false;
						this.userInfo = null;
					}
				})
			},
			goLogin() {
				if (!this.login) {
					uni.navigateTo({
					    url: '/pages/user/login'
					});
				} else {
					uni.navigateTo({
					    url: '/pages/user/ucenter'
					});
				}
			},
			feedback () {
				uni.navigateTo({
					url: '/pages/user/feedback'
				})
			},
			setting () {
				uni.navigateTo({
					url: '/pages/user/setting'
				})
			},
			about () {
				uni.navigateTo({
					url: '/pages/user/about'
				})
			},
			punch () {
				uni.navigateTo({
					url: '/pages/user/punch'
				})
			},
			download () {
				uni.navigateTo({
					url: '/pages/download/index'
				})
			},
			ucenter() {
				if (!this.login) {
					uni.navigateTo({
						url: '/pages/user/login'
					})
				}else{
					uni.navigateTo({
					    url: '/pages/user/ucenter'
					});
				}
			},
			history() {
				if (!this.login) {
					uni.navigateTo({
						url: '/pages/user/login'
					})
				}else{
					uni.navigateTo({
					    url: '/pages/user/history'
					});
				}
			},
			vip() {
				if (!this.login) {
					uni.navigateTo({
						url: '/pages/user/login'
					})
				}else{
					uni.navigateTo({
						url: '/pages/user/child/paynumber'
					});
				}
			}
		},
		onShow() {
			this.init();
		}
	}
</script>

<style>
	@font-face {
		font-family: texticons;
		font-weight: normal;
		font-style: normal;
		src: url('https://at.alicdn.com/t/font_984210_5cs13ndgqsn.ttf') format('truetype');
	}

	page,
	view {
		display: flex;
	}

	.center {
		flex-direction: column;
	}

	.logo {
		width: 750upx;
		height: 240upx;
		padding: 20upx;
		box-sizing: border-box;
		background-color: #161827;
		display: flex;
		flex-direction: row;
		align-items: center;
		color: #dae1f2;
	}

	.logo-hover {
		opacity: 0.8;
	}

	.logo-img {
		width: 150upx;
		height: 150upx;
		border-radius: 150upx;
	}

	.logo-title {
		height: 150upx;
		flex: 1;
		align-items: center;
		justify-content: space-between;
		flex-direction: row;
		margin-left: 20upx;
	}

	.uer-name {
		height: 60upx;
		line-height: 60upx;
		font-size: 38upx;
		color: #dae1f2;
	}
	
	.username-box {
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		text-align: left;
	}
	
	.uer-group {
		font-size: 22rpx;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		text-align: left;
	}

	.go-login.navigat-arrow {
		font-size: 38upx;
		color: #dae1f2;
	}

	.login-title {
		height: 150upx;
		align-items: self-start;
		justify-content: center;
		flex-direction: column;
		margin-left: 20upx;
	}

	.center-list {
		background-color: #dae1f2;
		margin-top: 20upx;
		width: 750upx;
		flex-direction: column;
	}

	.center-list-item {
		height: 90upx;
		width: 750upx;
		box-sizing: border-box;
		flex-direction: row;
		padding: 0upx 20upx;
	}
	
	.solid-bottom::after {
	    border-bottom: 0.5px solid #23273f!important;
	}

	.navigat-arrow {
		height: 90upx;
		width: 40upx;
		line-height: 90upx;
		font-size: 34upx;
		color: #555;
		text-align: right;
		font-family: texticons;
	}
</style>