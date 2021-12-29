<template>
	<view class="pages">
		<view class="cu-form-group solid-bottom">
			<view class="title">兑换码</view>
			<input placeholder="请输入兑换码" v-model="formData.code"></input>
		</view>
		<view class="padding flex flex-direction">
			<button :loading="saveLoading" :disabled="saveLoading" class="cu-btn bg-my-red margin-tb-sm lg" @click="save">提交</button>
		</view>
	</view>
</template>

<script>
	export default{
		data() {
			return {
				formData: {
					code: '',
				},
				saveLoading: false
			}
		},
		onShow() {
			let _this = this;
			uni.getClipboardData({
				success: function (res) {
					if (res.data.length == 32) {
						_this.formData.code = res.data;
					}
				}
			});
		},
		methods:{
			save () {
				let _this = this, userinfo = uni.getStorageSync('userinfo');
				_this.saveLoading = true;
				_this.$http.post('/codekey/codekey/useCodekey', _this.formData).then((res)=>{
					_this.saveLoading = false;
					if (res.code == 200) {
						uni.showModal({
							title: '提示',
							content: res.msg,
							showCancel: false,
							success() {
								uni.navigateBack();
							}
						})
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
