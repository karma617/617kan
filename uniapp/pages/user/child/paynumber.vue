<template>
	<view class="pages">
		<view class="padding-xs flex align-center">
			<view class="flex-sub text-center">
				<view class="text-lg padding">
					<text class="text-black">当前剩余：{{numbers}} 次</text>
				</view>
			</view>
		</view>
		<view class="grid col-1 padding-15" @click="goPrase()">
			<view class="bg-my-red-cur padding radius text-center shadow-blur">
				<view class="text-lg">去下载</view>
			</view>
		</view>
		<view class="grid col-1 padding-15" v-for="(item,index) in goodsList" :key="index" @click="gopay(item.id)">
			<view class="padding radius text-center shadow-blur" :class="className[Math.floor(Math.random() * 4)]">
				<view class="text-lg">{{item.goods_name}}</view>
				<view class="margin-top-sm text-Abc">{{item.money}}元</view>
			</view>
		</view>

	</view>
</template>

<script>
	export default {
		data() {
			return {
				numbers: '',
				className: [
					'bg-gradual-blue',
					'bg-gradual-pink',
					'bg-gradual-red',
					'bg-gradual-orange'
				],
				goodsList: []
			}
		},
		methods:{
			goPrase () {
				let userinfo = uni.getStorageSync('userInfo');
				if (userinfo.number <= 0) {
					uni.showToast({
						title: '当前剩余次数不足，请充值',
						icon: 'none'
					})
				}else{
					uni.navigateTo({
					    url: '/pages/user/child/parsevideo'
					});
				}
			},
			gopay (id) {
				let _this = this;
				uni.showModal({
				    title: '下单提醒',
				    content: "是否创建订单？",
				    success: function (res) {
				        if (res.confirm) {
							_this.$http.post('/order/order/createOrder',{goods_id: id}).then((res)=>{
								if (res.code != 200) {
									uni.showToast({
										title: res.msg || '下单失败',
										icon: 'none'
									})
									return false;
								}
								uni.navigateTo({
									url: '/pages/pay/index?order_sn=' + res.data.order_sn + '&money=' + res.data.money + '&oid=' + res.data.order_id
								});
							}).catch((err)=>{
								
							})
				        }else{
							return false;
						}
				    }
				});
			},
			getGoodsLists () {
				let _this = this;
				_this.$http.post('/goods/goods/getGoodsLists',{c: 1}).then((res)=>{
					if (res.code != 200) {
						uni.showToast({
							title: res.msg || '获取失败',
							icon: 'none'
						})
						return false;
					}
					_this.goodsList = res.data;
				}).catch((err)=>{
					
				})
			},
			getNumbers () {
				let _this = this;
				_this.$http.post('/user/user/getNumbers',{}).then((res)=>{
					if (res.code != 200) {
						uni.showToast({
							title: res.msg || '获取失败',
							icon: 'none'
						})
						return false;
					}
					_this.numbers = res.data;
				}).catch((err)=>{
					
				})
			}
		},
		mounted() {
			this.getGoodsLists();
		},
		onShow() {
			// #ifdef H5
				uni.switchTab({
					url: '/pages/user/index'
				})
			//#endif
			var _this = this;
			let userinfo = uni.getStorageSync('userInfo');
			if (!userinfo) {
				uni.navigateTo({
					url: '/pages/user/login'
				})
			}else{
				_this.getNumbers();
			}
		}
	}
</script>

<style>
</style>
