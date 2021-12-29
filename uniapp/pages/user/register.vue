<template>
	<view class="zai-box pages">
		<image src="../../static/zaizai-login/register.png" mode='aspectFit' class="zai-logo"></image>
		<view class="zai-title">云影评</view>
		<view class="zai-form">
			<view class="cu-form-group">
				<input class="zai-input" maxlength="11" type="number" v-model="formValue.account" placeholder-class placeholder="请输入手机号" />
			</view>
			<view class="cu-form-group">
				<input class="zai-input" v-model="formValue.password" placeholder-class password placeholder="请输入密码"/>
			</view>
			<view class="cu-form-group">
				<input class="zai-input" v-model="formValue.repassword" placeholder-class password placeholder="请再输入一次密码"/>
			</view>
			<button class="zai-btn bg-my-red" @click="register">立即注册</button>
			<navigator url="../user/login" open-type='navigateBack' hover-class="none" class="zai-label">已有账号，点此去登录</navigator>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				formValue: {
					type: 'reg',
					account: '',
					password: '',
					repassword: '',
					group_id: 1,
					drice_id: ''
				}
			}
		},
		methods: {
			register () {
				var _this = this;
				if (!_this.formValue.account || !_this.formValue.password) {
					uni.showToast({title: '请输入帐号和密码',icon: 'none'});
					return false;
				}
				if (!_this.formValue.repassword) {
					uni.showToast({title: '请输入确认密码',icon: 'none'});
					return false;
				}
				if (_this.formValue.password != _this.formValue.repassword) {
					uni.showToast({title: '两次密码不一致',icon: 'none'});
					return false;
				}
				// #ifdef APP-PLUS
				plus.device.getInfo({
					success:function(e){
						_this.formValue.drice_id = e.uuid;
						_this.doRegister();
					}
				});
				// #endif
				// #ifndef APP-PLUS
					_this.doRegister();
				// #endif
			},
			doRegister () {
				var _this = this;
				_this.$http.post('/user/auth/dosth', _this.formValue).then((res)=>{
					if (res.code == 200) {
						uni.showToast({title: "注册成功！\n请加Q群（516769607）\n联系群主进行帐号审核", icon: 'none'});
						uni.setStorageSync('userInfo', res.data);
						uni.setStorageSync('token', res.data.token);
						uni.switchTab({
							url: '/pages/user/index'
						})
					}else if(res.code == 617){
						// #ifdef APP-PLUS
						uni.showModal({
							title: "注册提示",
							content: res.msg,
							confirmText: '去加群',
							success: function (res) {
								if (res.confirm) {
										plus.runtime.openURL('mqqopensdkapi://bizAgent/qm/qr?url=http%3A%2F%2Fqm.qq.com%2Fcgi-bin%2Fqm%2Fqr%3Ffrom%3Dapp%26p%3Dandroid%26k%3DoUlpgIVkRsGp8s8pAs5AEmdaTz9NM9_Z',function (res) {  
											plus.nativeUI.alert("本机没有安装QQ，无法启动");  
										});
								} else if (res.cancel) {
									uni.switchTab({
										url: "/pages/user/index"
									})
								}
							}
						})
						// #endif
						// #ifndef APP-PLUS
							uni.showModal({
								title: "注册提示",
								content: res.msg,
								showCancel: false,
								success: function (res) {
									uni.switchTab({
										url: "/pages/user/index"
									})
								}
							})
						// #endif
					}else{
						uni.showToast({
							title: res.msg,
							icon: "none"
						})
					}
				}).catch((err)=>{
					
				})
			}
		}
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
		margin-top: 60upx;
	}
	.zai-btn:after{
		border: 0;
	}
	/*按钮点击效果*/
	.zai-btn.button-hover{
		transform: translate(1upx, 1upx);
	}
</style>