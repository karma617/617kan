<template>
	<view class="pages">
		<view class="cu-form-group">
			<view class="title">订 单 号</view>
			<input :placeholder="order_sn" disabled="disabled"></input>
		</view>
		<view class="cu-form-group">
			<view class="title">订单金额</view>
			<input :placeholder="money" disabled="disabled"></input>
		</view>
		<view class="cu-form-group">
			<scroll-view scroll-x class="bg-my-black nav text-center">
				<view class="cu-item cur">
					支付方式
				</view>
			</scroll-view>
		</view>
		<view class="cu-bar bg-my-black margin-top">
			<view class="radio-box">
				<helang-checkbox ref="checkbox" @change="valueChange"></helang-checkbox>
			</view>
		</view>
		<view class="go-pay"> 
			<button @click="gopay" class="cu-btn block bg-my-gray btn-clear padding-tb-25" :loading="loading" :disabled="loading">去支付</button>
		</view>
	</view>
</template>

<script>
	import helangCheckbox from "@/components/helang-checkbox/helang-checkbox.vue"
	export default {
		components: {
		    "helang-checkbox":helangCheckbox
		},
		data() {
			return {
				order_sn: '',
				money: '',
				formPay:{
					order_id: 0,
					pay_code: 'alipay_app'
				},
				loading: false
			}
		},
		onLoad(e) {
			this.order_sn = e.order_sn;
			this.money = '￥' + e.money + ' 元';
			this.formPay.order_id = e.oid;
			this.loading = false;
			uni.setStorageSync('order_sn', this.order_sn);
		},
		methods:{
			valueChange(data){
				this.formPay.pay_code = data.value;
			},
			gopay () {
				let _this = this;
				let contents = '';
				if (this.formPay.pay_code == 'wechat_app') {
					contents = '请保存二维码到微信内扫码支付，一定确保金额准确，若出现未到账情况，请加Q群联系群主';
				}else{
					contents = '即将跳转到支付宝支付';
				}
				
				uni.showModal({
				    title: '支付提醒',
				    content: contents,
				    success: function (res) {
				        if (res.confirm) {
							_this.loading = true;
							_this.go();
				        }else{
							return false;
						}
				    }
				});
			},
			go() {
				let _this = this;
				_this.$http.post('/pay/pay/gopay',_this.formPay).then((res)=>{
					if (res.code != 200) {
						uni.showToast({
							title: res.msg || '对接接口失败',
							icon: 'none'
						})
						return false;
					}
					let app = '微信';
					if (this.formPay.pay_code == 'wechat_app') {
						app = encodeURIComponent('http://qr.liantu.com/api.php?&w=1000&text=' + res.data.json_data.payUrl);
					}else{
						uni.showToast({
							title: '正在唤起支付宝',
							icon: 'none'
						})
						app = res.data.json_data.qr_code;
					}
					uni.setStorageSync('paying', 1);
					uni.setStorageSync('backurl', '/pages/user/index');
					let io = plus.os.name.toLowerCase();
					if (io == 'android') {
						uni.navigateTo({
							url: '/pages/webview/webview?url=' + app
						});
					}else{
						plus.runtime.openURL(app); 
					}
				}).catch((err)=>{
					
				})
			}
		},
		mounted() {
			this.$refs.checkbox.set({
				type: 'radio',	// 类型：单选框
				index: 0,		// 默认选中的项
				column: 1,		// 分列
				list:[
					// {
					// 	id: 1,
					// 	value: 'wechat_app',
					// 	text: '微信'
					// },
					{
						id: 2,
						value: 'alipay_app',
						text: '支付宝'
					}
				]
			});
		}
	}
</script>

<style>
	.radio-box{
		width: 100%;
	}
	.go-pay {
		position: fixed;
		width: 100%;
		bottom: 0;
	}
</style>
