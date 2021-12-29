<template>
	<view class="pages padding">
		<!-- 广告 -->
		<Adview :adSwitchKg="adSwitchKg" :homeADList="homeADList"></Adview>
		<scroll-view id="video-list-box" :style="videoListBoxStyle" scroll-y="true" @scrolltolower="getMore()">
			<view class="flex flex-wrap grid col-3 grid-square" v-if="videoLists.length > 0">
				<!-- 列表开始 -->
				<view class="movie-box video-list-item" v-for="(item,index) in videoLists" @click="goDetail(item.vod_id)" :key="index">
					<view class="bg-img  movie-img image">
						<image :lazy-load="true" style="height: 300upx;" :src="item.vod_pic.search('http') >= 0 ? item.vod_pic : imageUrl + item.vod_pic"></image>
						<view class="text-sm cu-tag bg-blue movie-text-box">
							<text class="text-white movie-text">{{item.vod_score}}</text>
							<text v-if="item.type_id_1 != 1" class="text-white movie-text">{{item.vod_name}}{{item.vod_remarks ? '-' + item.vod_remarks : ''}}</text>
							<text v-else class="text-white movie-text">{{item.vod_name}}</text>
						</view>
					</view>
				</view>
				<!-- 列表结束 -->
			</view>
			<view class="mask" v-else-if="netError" @click="refresh">
				<view class="text-df refresh-text text-gray">
					<image class="refresh-img" src="../../static/refersh.png"></image>
					<view class="">{{waringText}}</view>
				</view>
			</view>
			<view class="mask" v-else>
				<view class="text-df refresh-text text-gray">
					<image class="refresh-img" :src="imgUrl"></image>
					<view class="" >{{waringText}}</view>
				</view>
			</view>
		</scroll-view>
	</view>
</template>

<script>
	import Adview from '@/components/home-ad/ad-view.vue';
	const util = require('@/static/js/util.js');
	export default {
		components: {
			Adview
		},
		data() {
			return {
				imageUrl: this.$helper.imageUrl,
				videoMap: {
					type: 0,
					page: 1,
					limit: 18
				},
				videoLists: [],
				videoListBoxStyle: '',
				imgUrl: '../../static/loading.png',
				netError: false,
				waringText: '正在加载...',
				adSwitchKg: 0,
				homeADList: null
			};
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
		mounted() {
			this.initUi(this.videoMap.type);
			this.getVideosList();
		},
		onLoad(option) {
			this.videoMap.type = option.type;
		},
		methods: {
			initUi (type) {
				var _this=this;
				
				switch(type){
					case '1':
						uni.setNavigationBarTitle({
						　　title:'热播电影'
						})
						break;
					case '2':
						uni.setNavigationBarTitle({
						　　title:'热播剧'
						})
						break;
					case '3':
						uni.setNavigationBarTitle({
						　　title:'二次元'
						})
						break;
					case '4':
						uni.setNavigationBarTitle({
						　　title:'综艺'
						})
						break;
					default:
						uni.setNavigationBarTitle({
						　　title:'热播电影'
						})
						break;
				}
				
				uni.getSystemInfo({
					success:(resu)=>{
						const query = uni.createSelectorQuery()
						query.select('#video-list-box').boundingClientRect()
						query.selectViewport().scrollOffset()
						query.exec(function(res){
							const boxHeight = resu.windowHeight - resu.statusBarHeight;
							_this.videoListBoxStyle= 'height: ' + boxHeight +'px';
						})
					},
					fail:(res)=>{}
				})
			},
			getVideosList (isRefresh = false) {
				uni.showLoading({title: '加载中',mask: true});
				var _this = this;
				_this.videoMap.page = !isRefresh ? 1 : _this.videoMap.page + 1;
				_this.$http.post('/videos/videos/getTypeVideoLists', _this.videoMap).then((res)=>{
					uni.hideLoading();
					if(res.data.list.length <= 0){
						uni.showToast({
							title: '没有啦~', 
							icon: "none"
						})
						_this.waringText = '暂无内容';
						_this.imgUrl = '../../static/noinfo.png';
					}
					_this.videoMap.limit = res.data.limit;
					//设置列表数据
					_this.videoLists = !isRefresh ? res.data.list : _this.videoLists.concat(res.data.list);
				}).catch((err)=>{
					uni.hideLoading();
					_this.waringText = '网络错误,点此重试';
					_this.netError = true;
				})
			},
			getMore: util.throttle(function(e) {
				this.getVideosList(true);
			}, 300),
			goDetail(vod_id) {
				uni.navigateTo({url:"../detail/index?id="+vod_id});
			},
		}
	}
</script>

<style>
</style>
