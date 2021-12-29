<template>
	<view class="pages">
		<view class="padding-xs flex align-center">
			<view class="flex-sub text-center">
				<view class="text-xsl padding">
					<text :class="payinfo[index].icon"></text>
				</view>
				<view class="padding">{{payinfo[index].text}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				url: '',
				payinfo: [
					{
						text: '支付成功',
						icon: 'cuIcon-roundcheckfill text-green'
					},
					{
						text: '待支付',
						icon: 'cuIcon-infofill text-orange'
					},
					{
						text: '支付失败，若已支付请加群联系群主处理',
						icon: 'cuIcon-roundclosefill text-red'
					},
				],
				index: 0,
				
			};
		},
		onBackPress() {
			let backurl = uni.getStorageSync('backurl');
			uni.switchTab({
				url: backurl,
				success() {
					uni.setStorageSync('backurl', null);
				}
			})
			return true;
		},
		onLoad(val) {
			uni.setStorageSync('paying', null);
		},
		methods: {
			getOrderStatus() {
				var _this = this;
				let order_sn = uni.getStorageSync('order_sn');
				if (order_sn) {
					_this.$http.post('/order/order/getOrderStatus',{order_sn: order_sn}).then((res)=>{
						if (res.code != 200) {
							uni.showToast({
								title: res.msg || '网络错误',
								icon: 'none'
							})
							return false;
						}
						switch (res.data){
							case 1:
								_this.index = 1;
								setTimeout(function(){
									_this.getOrderStatus();
								}, 1000);
								break;
							case 2:
								_this.index = 0;
								break;
							default:
								_this.index = 2;
								break;
						}
					}).catch((err)=>{
						
					})
				}else{
					uni.showToast({
						title: '未获取到订单号',
						icon: 'none'
					})
				}
			}
		},
		mounted() {
			this.getOrderStatus()
		}
	}
</script>

<style>
</style>
