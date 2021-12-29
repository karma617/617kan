<template>
	<view class="pages">
		<view class="cu-form-group solid-bottom">
			<view class="title">旧密码</view>
			<input placeholder="旧密码" v-model="formData.oldpassword"></input>
		</view>
		<view class="cu-form-group solid-bottom">
			<view class="title">新密码</view>
			<input placeholder="新密码" v-model="formData.password"></input>
		</view>
		<view class="cu-form-group solid-bottom">
			<view class="title">再次输入新密码</view>
			<input placeholder="再次输入新密码" v-model="formData.repassword"></input>
		</view>
		<view class="padding flex flex-direction">
			<button :loading="saveLoading" :disabled="saveLoading" class="cu-btn bg-my-red margin-tb-sm lg" @click="save">保存</button>
		</view>
	</view>
</template>

<script>
	export default{
		data() {
			return {
				formData: {
					password: '',
					oldpassword: '',
					repassword: '',
				},
				saveLoading: false
			}
		},
		methods:{
			save () {
				let _this = this, userinfo = uni.getStorageSync('userinfo');
				_this.saveLoading = true;
				_this.$http.post('/user/user/changePassword', _this.formData).then((res)=>{
					uni.showToast({title: res.msg, icon: 'none', duration: 2000});
					if (res.code == 200) {
						setTimeout((v => {
							uni.navigateBack();
						}), 1500);
					} else {
						_this.saveLoading = false;
					}
				}).catch((err)=>{
					_this.saveLoading = false;
					
				})
			}
		}
	}
</script>

<style>
	.solid-bottom::after {
	    border-bottom: 0.5px solid #23273f!important;
	}
</style>
