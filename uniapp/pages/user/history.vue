<template>
	<view class="page">
		<view class="list-box">
			<block v-for="(item,index) in historyList" :key="index">
				<view class="list-item">
					<view class="list-item-img">
						<image :src="item.img.search('http') >= 0 ? item.img : imageUrl + item.img"></image>
					</view>
					<view class="list-item-title">
						<view class="item-title-top">{{item.title}}</view>
						<view class="item-title-bottom" v-if="!isShowxx">
							<view class="item-title-bottom-left">
								第{{parseInt(item.index) + 1}}集，已观看{{getCurrentTime(item.title, item.index)}}
							</view>
							<view class="item-title-bottom-right" @click="goDetail(item.id, item.index)">
								<view class="item-title-bottom-right-button bg-my-red">继续观看</view>
							</view>
						</view>
						<view class="item-title-bottom" v-else>
							<view class="item-title-bottom-left">
								
							</view>
							<view class="item-title-bottom-right" @click="goDetail(item.id, item.index)">
								<view class="item-title-bottom-right-button bg-my-red">再次查看</view>
							</view>
						</view>
					</view>
				</view>
			</block>
		</view>
		<view class="mask" v-if="historyList.length <= 0">
			<view class="text-df refresh-text text-gray">
				<image class="refresh-img" src="../../static/noinfo.png"></image>
				<view class="" >{{waringText}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	import hex_md5 from '@/common/md5.js';
	export default {
		data() {
			return {
				imageUrl: this.$helper.imageUrl,
				apiImageUrl: this.$helper.apiImageUrl,
				historyList: [],
				isShowxx: null,
				waringText: '暂未浏览历史'
			}
		},
		onShow() {
			this.historyList = uni.getStorageSync('historyList');
			let config = uni.getStorageSync('sys_config');
			this.isShowxx = isNaN(parseInt(config.show_xx)) ? true : parseInt(config.show_xx);
			if (!this.isShowxx) {
				uni.setNavigationBarTitle({
					title: '观看历史'
				})
				this.waringText = '暂无观看历史';
			}
		},
		methods: {
			goDetail (id, index) {
				if (parseInt(id) > 0) {
					uni.navigateTo({url:"../detail/index?id=" + id + "&index=" + index});
				}
			},
			getCurrentTime(title,index){
				let time = uni.getStorageSync(hex_md5(title + index));
				time = isNaN(parseInt(time)) ? 0 : parseInt(time);
				if (time < 60) {
					return parseInt(time) + '秒';
				}
				
				if (time > 60 && (time / 60) < 60) {
					return parseInt(time / 60) + '分钟';
				}
				
				if ((time / 60) > 60) {
					return parseInt(time / 60 / 60) + '小时';
				}
			}
		}
	}
</script>

<style>
	.page{
		padding: 20rpx;
		background-color: #161827!important;
		min-height: 100vh;
	}
	.list-box{
		display: flex;
		flex-direction: column;
		align-items: center;
	}
	.list-item {
		width: 100%;
		display: flex;
		flex-direction: row;
		padding: 20rpx 0;
	}
	.list-item-img image{
		width: 200rpx;
		height: 280rpx;
	}
	.list-item-title{
		width: 100%;
		padding: 20rpx;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
	}
	.item-title-bottom {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
	}
	.item-title-bottom-left{
		font-size: 23rpx;
		color: #666;
		padding: 10rpx 0;
	}
	.item-title-bottom-right-button{
		padding: 10rpx 20rpx;
		border-radius: 10rpx;
		font-size: 24rpx;
	}
</style>
