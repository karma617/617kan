<template>
	<view class="zai-box pages">
		<image src="../../static/zaizai-login/login.png" mode='aspectFit' class="zai-logo"></image>
		<view class="zai-title">{{appName}}</view>
		<!-- #ifdef APP-PLUS -->
			<view class="zai-form">
				<view class="cu-form-group">
					<input class="zai-input" maxlength="11" type="number" v-model="formValue.account" placeholder="请输入手机号" />
				</view>
				<view class="cu-form-group">
					<input class="zai-input" password v-model="formValue.password" placeholder="请输入密码"/>
				</view>
				<!-- <view class="zai-label">忘记密码？</view> -->
				<button @click="login" class="zai-btn">立即登录</button>
				<navigator url="../user/register" hover-class="none" class="zai-label">还没有账号？点此注册</navigator>
			</view>
		<!-- #endif -->
		<!-- #ifdef H5 -->
			<view class="zai-form">
				<view class="cu-form-group">
					<input class="zai-input" maxlength="11" type="number" v-model="formValue.account" placeholder="请输入手机号" />
				</view>
				<view class="cu-form-group">
					<input class="zai-input" password v-model="formValue.password" placeholder="请输入密码"/>
				</view>
				<!-- <view class="zai-label">忘记密码？</view> -->
				<button @click="login" class="zai-btn bg-my-red">立即登录</button>
				<navigator url="../user/register" hover-class="none" class="zai-label">还没有账号？点此注册</navigator>
			</view>
		<!-- #endif -->
		<!-- #ifdef MP-WEIXIN -->
			<view class="zai-form" v-if="!showBindMobile">
				<button type="primary" :loading="getUserInfoLoading" class="zai-btn bg-my-red" open-type="getUserInfo" @getuserinfo="getuserinfo">授权登录</button>
			</view>
			<view class="zai-form" v-else>
				<view class="flex-sub text-center">
					<view class="solid-bottom text-sub padding tips">
						<text class="text-gray">绑定成功后可使用该手机号登录APP</text>
						<text class="text-gray">初始登录密码为：123456</text>
						<text class="text-gray">可到用户中心修改密码</text>
					</view>
				</view>
				<view class="cu-form-group">
					<input class="zai-input" maxlength="11" type="number" v-model="formValue.account" placeholder="请输入手机号" />
				</view>
				<button @click="bingMobile" class="zai-btn bg-my-red">绑定并登录</button>
			</view>
		<!-- #endif -->
	</view>
</template>
 
<script>
	export default {
		data() {
			return {
				formValue: {
					type: 'login',
					account: '',
					password: ''
				},
				showBindMobile: false,
				bindForm: {
					mobile: ''
				},
				appName: this.$helper.appName,
				loginCode: '',
				getUserInfoLoading: false,
				goback: false
			}
		},
		onLoad(options) {
			if (options.hasOwnProperty('to')) {
				this.goback = true;
			}
		},
		onShow() {
			var _this = this;
			uni.login({
				provider: 'weixin',
				success: function (loginRes) {
					_this.loginCode = loginRes.code;
				},
			})
		},
		methods: {
			getuserinfo (e) {
				var _this = this, userinfo = e.detail;
				_this.getUserInfoLoading = true;
				if (_this.loginCode.length <= 0) {
					_this.getUserInfoLoading = false;
					uni.showToast({
						title: '获取code失败',
						icon: 'none'
					})
					return false;
				}
				userinfo.code = _this.loginCode;
				userinfo.iv = encodeURIComponent(userinfo.iv);
				userinfo.encryptedData = encodeURIComponent(userinfo.encryptedData);
				_this.$http.post('/user/wechat/mplogin', userinfo).then((res)=>{
					_this.getUserInfoLoading = false;
					if (200 == res.code) {
						if (res.data.has_account == 0) {
							_this.showBindMobile = true;
							_this.bindForm = res.data;
							return;
						}else{
							uni.setStorageSync('userInfo', res.data);
							uni.setStorageSync('token', res.data.token);
							if (_this.goback) {
								uni.navigateBack();
								return true;
							}
							uni.switchTab({
								url: '/pages/user/index'
							})
						}
					} else {
						uni.showToast({
							title: res.msg + '请重试' || '请求失败，请重试',
							icon: 'none'
						})
					}
				}).catch((err)=>{
					_this.getUserInfoLoading = false;
					
				})
			},
			bingMobile () {
				var _this = this;
				uni.showLoading({
					mask: true
				});
				_this.bindForm.mobile = _this.formValue.account;
				_this.$http.post('/user/wechat/bindMobile', _this.bindForm).then((res)=>{
					uni.hideLoading();
					if (200 == res.code) {
						uni.setStorageSync('userInfo', res.data);
						uni.setStorageSync('token', res.data.token);
						uni.switchTab({
							url: '/pages/user/index'
						})
					} else {
						uni.showToast({
							title: res.msg || '绑定失败',
							icon: 'none'
						})
					}
				}).catch((err)=>{
					
				})
			},
			login () {
				var _this = this;
				if (!_this.formValue.account || !_this.formValue.password) {
					uni.showToast({title: '请输入帐号和密码',icon: 'none'});
					return false;
				}
				_this.$http.post('/user/auth/dosth', _this.formValue).then((res)=>{
					uni.showToast({title: res.msg, icon: 'none'});
					if (res.code == 200) {
						uni.setStorageSync('userInfo', res.data);
						uni.setStorageSync('token', res.data.token);
						if (_this.goback) {
							uni.navigateBack();
							return true;
						}
						uni.switchTab({
							url: '/pages/user/index'
						})
					}
				}).catch((err)=>{
					
				})
			}
		},
	}
</script>

<style>
	.cu-form-group{
		border: none!important;
		background-color: #161827!important;
	}
	.zai-box{
		padding: 0 100upx;
		position: relative;
	}
	.zai-logo{
		width: 100%;
		height: 310upx;
	}
	.zai-title{
		position: absolute;
		top: 0;
		line-height: 360upx;
		font-size: 68upx;
		color: #fff;
		text-align: center;
		width: 100%;
		margin-left: -100upx;
	}
	.zai-form{
		margin-top: 300upx;
	}
	.zai-input{
		background: #e2f5fc;
		margin-top: 30upx;
		border-radius: 100upx;
		padding: 0 40upx;
		font-size: 36upx;
		height: 80upx!important;
		min-height: 80upx!important;
		line-height: 80upx!important;
	}
	.input-placeholder, .zai-input{
		color: #94afce;
	}
	.zai-label{
		padding: 60upx 0;
		text-align: center;
		font-size: 30upx;
		color: #a7b6d0;
	}
	.zai-btn{
		/* #ifdef APP-PLUS */
		background: rgba(72,179,253);
		/* #endif */
		/* #ifndef APP-PLUS */
		background: rgb(72,179,253);
		/* #endif */
		color: #fff;
		border: 0;
		border-radius: 100upx;
		font-size: 36upx;
		margin-top: 80upx;
	}
	.zai-btn:after{
		border: 0;
	}
	/*按钮点击效果*/
	.zai-btn.button-hover{
		transform: translate(1upx, 1upx);
	}
	.zai-form .tips{
		display: flex;
		flex-direction: column;
	}
</style>
