<template>
	<view class="cate-body pages">
		<view class="fixed">
			<scroll-view scroll-x class="bg-my-black nav" scroll-with-animation :scroll-left="scrollLeft">
				<view class="cu-item" :class="index==TabCur?'text-my-red cur':'text-my-white'" v-for="(item,index) in videosTypeList" 
				:key="index" @tap="tabSelect" :data-id="index" :data-tid="item.type_id">
					{{item.type_name}}
				</view>
			</scroll-view>
		</view>
		<view class="list-box">
			<view class="filter-box">
				<scroll-view scroll-x class="nav" scroll-with-animation :scroll-left="scrollMap1Left">
					<view class="cu-tag radius filter-item" :class="index==TabMap1Cur?'bg-my-gray-cur':'bg-my-gray'" 
					v-if="videosTypeList[TabCur]"
					v-for="(item,index) in videosTypeList[TabCur]._c"
					:key="index" @tap="videoMap1Select" :data-id="index" :data-area="item.type_id">
						{{item.type_name}}
					</view>
				</scroll-view>
			</view>
			<!-- <view class="padding-7"></view>
			<view class="filter-box">
				<scroll-view scroll-x class="nav" scroll-with-animation :scroll-left="scrollMap2Left">
					<view class="cu-tag radius filter-item" :class="index==TabMap2Cur?'bg-my-gray-cur':''"
					v-for="(item,index) in videosMap2List"
					:key="index" @tap="videoMap2Select" :data-id="index" :data-area="item.vod_class">
						{{item.vod_class}}
					</view>
				</scroll-view>
			</view> -->
			<view class="padding-7"></view>
			<!-- #ifdef APP-PLUS -->
			<view class="filter-box" v-if="videosTypeList[TabCur] && videosTypeList[TabCur].hasOwnProperty('_c')">
			<!-- #endif -->
			<!-- #ifdef MP-WEIXIN -->
			<view class="filter-box">
			<!-- #endif -->
				<scroll-view scroll-x class="nav" scroll-with-animation :scroll-left="scrollMap3Left">
					<view class="cu-tag radius filter-item" :class="index==TabMap3Cur?'bg-my-gray-cur':'bg-my-gray'"
					v-for="(item,index) in videosMap3List"
					:key="index" @tap="videoMap3Select" :data-id="index" :data-area="item.vod_year">
						{{item.vod_year}}
					</view>
				</scroll-view>
			</view>
			<view class="padding-7"></view>
			<view class="bg-my-black">
				<scroll-view id="video-list-box" :style="videoListBoxStyle" scroll-y="true" @scrolltolower="getMore()">
					<view class="flex flex-wrap grid col-3 grid-square" v-if="videoLists.length > 0">
						<!-- 列表开始 -->
						<view  class="movie-box video-list-item" v-for="(item,index) in videoLists" @click="goDetail(item.vod_id)" :key="index">
							<view class="bg-img  movie-img image">
								<image :lazy-load="true" style="height: 300upx;" :src="item.vod_pic.search('http') >= 0 ? item.vod_pic : imageUrl + item.vod_pic"></image>
								<view class="text-sm cu-tag movie-text-box">
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
		</view>
		<!-- #ifdef APP-PLUS -->
		<view class="padding"></view>
		<!-- #endif -->
	</view>
</template>
<script>
	const util = require('@/static/js/util.js');
	export default {
		data() {
			return {
				imageUrl: this.$helper.imageUrl,
				imgUrl: '../../static/loading.png',
				netError: false,
				waringText: '正在加载...',
				downOption: {
					auto: false //是否在初始化后,自动执行下拉回调callback; 默认true
				},
				videosTypeList: [],
				videosMap1List: [],
				videosMap2List: [],
				videosMap3List: [],
				loading: false,
				TabCur: 0,
				TabMap1Cur: 0,
				TabMap2Cur: 0,
				TabMap3Cur: 0,
				scrollLeft: 0,
				scrollMap1Left: 0,
				scrollMap2Left: 0,
				scrollMap3Left: 0,
				videoMap: {
					type_id: '1',
					area: '7',
					year: '全部',
					page: 1,
					limit: 18
				},
				videoLists: [],
				videoListBoxStyle: ''
			};
		},
		onTabItemTap (e) {
			if (1 == e.index) {
				uni.removeStorageSync('videosType');
				uni.removeStorageSync('videosMap1');
				uni.removeStorageSync('videosMap2');
				uni.removeStorageSync('videosMap3');
				this.initInfo(true);
			}
		},
		methods: {
			tabSelect(e) {
				this.TabCur = e.currentTarget.dataset.id;
				this.scrollLeft = (e.currentTarget.dataset.id - 1) * 60
				this.videoMap.type_id = e.currentTarget.dataset.tid;
				this.getVideosList(true);
			},
			videoMap1Select(e) {
				this.TabMap1Cur = e.currentTarget.dataset.id;
				this.scrollMap1Left = (e.currentTarget.dataset.id - 1) * 60
				this.videoMap.area = e.currentTarget.dataset.area;
				this.getVideosList(true);
			},
			videoMap2Select(e) {
				this.TabMap2Cur = e.currentTarget.dataset.id;
				this.scrollMap2Left = (e.currentTarget.dataset.id - 1) * 60
				this.videoMap.class = e.currentTarget.dataset.area;
				this.getVideosList(true);
			},
			videoMap3Select(e) {
				this.TabMap3Cur = e.currentTarget.dataset.id;
				this.scrollMap3Left = (e.currentTarget.dataset.id - 1) * 60
				this.videoMap.year = e.currentTarget.dataset.area;
				this.getVideosList(true);
			},
			goDetail(vod_id) {
				uni.navigateTo({url:"../detail/index?id="+vod_id});
			},
			getVideosType () {
				let _this = this;
				_this.$http.post('/videos/videos/getVideoTypeLists',{}).then((res)=>{
					_this.videosTypeList = res.data;
				}).catch((err)=>{
					_this.waringText = '网络错误,点此重试';
					_this.netError = true;
				})
			},
			getVideosList (isRefresh = false) {
				uni.showLoading({title: '加载中',mask: true});
				var _this = this;
				_this.videoMap.page = isRefresh ? 1 : _this.videoMap.page + 1;
				_this.$http.post('/videos/videos/getVideoLists', _this.videoMap).then((res)=>{
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
					uni.$emit('imgRefresh')
					_this.videoLists = isRefresh ? res.data.list : _this.videoLists.concat(res.data.list);
				}).catch((err)=>{
					uni.hideLoading();
					_this.waringText = '网络错误,点此重试';
					_this.netError = true;
				})
			},
			getVideosMap1 () {
				let videosMap1 = uni.getStorageSync('videosMap1') ? JSON.parse(uni.getStorageSync('videosMap1')) : false;
				if(!videosMap1){
					this.$http.post('/videos/videos/getVideoArea',{}).then((res)=>{
						uni.setStorageSync('videosMap1', JSON.stringify(res.data));
						this.videosMap1List = res.data;
					}).catch((err)=>{
						
						_this.waringText = '网络错误,点此重试';
						_this.netError = true;
					})
				}
				this.videosMap1List = videosMap1;
			},
			getVideosMap2 () {
				let videosMap2 = uni.getStorageSync('videosMap2') ? JSON.parse(uni.getStorageSync('videosMap2')) : false;
				if(!videosMap2){
					this.$http.post('/videos/videos/getVideoClass',{}).then((res)=>{
						uni.setStorageSync('videosMap2', JSON.stringify(res.data));
						this.videosMap2List = res.data;
					}).catch((err)=>{
						
						_this.waringText = '网络错误,点此重试';
						_this.netError = true;
					})
				}
				this.videosMap2List = videosMap2;
			},
			getVideosMap3 () {
				let videosMap3 = uni.getStorageSync('videosMap3') ? JSON.parse(uni.getStorageSync('videosMap3')) : false;
				if(!videosMap3){
					this.$http.post('/videos/videos/getVideoYear',{}).then((res)=>{
						uni.setStorageSync('videosMap3', JSON.stringify(res.data));
						this.videosMap3List = res.data;
					}).catch((err)=>{
						
						_this.waringText = '网络错误,点此重试';
						_this.netError = true;
					})
				}
				this.videosMap3List = videosMap3;
			},
			getMore: util.throttle(function(e) {
				this.getVideosList();
			}, 300),
			initVideoListBoxUi(){
				var _this = this, boxHeight = 0;
				uni.getSystemInfo({
					success:(resu)=>{
						const query = uni.createSelectorQuery()
						query.select('#video-list-box').boundingClientRect()
						query.selectViewport().scrollOffset()
						query.exec(function(res){
							// #ifdef APP-PLUS
								boxHeight = resu.windowHeight - res[0].top - resu.windowBottom;
								_this.videoListBoxStyle= 'height: ' + boxHeight +'px';
							// #endif
							// #ifndef APP-PLUS
								boxHeight = resu.windowHeight - res[0].top;
								_this.videoListBoxStyle= 'height: ' + boxHeight +'px';
							// #endif
							// #ifdef H5
								_this.videoListBoxStyle= 'padding:20rpx;height: ' + boxHeight +'px';
							// #endif
						})
					},
					fail:(res)=>{}
				})
			},
			refresh () {
				this.netError = false;
				this.initInfo(true);
			},
			initInfo(isInit = true) {
				this.netError = false;
				uni.showLoading({title: '加载中'});
				this.TabCur = 0;
				this.TabMap1Cur = 0;
				this.TabMap2Cur = 0;
				this.TabMap3Cur = 0;
				this.scrollLeft = 0;
				this.scrollMap1Left = 0;
				this.scrollMap2Left = 0;
				this.scrollMap3Left =  0;
				this.videoMap = {
					type_id: '1',
					area: '全部',
					year: '全部',
					page: 1,
					limit: 18
				};
				this.getVideosType();
				this.getVideosMap1();
				this.getVideosMap2();
				this.getVideosMap3();
				uni.hideLoading();
				this.getVideosList(isInit);
			}
		},
		onLoad(){
			this.initInfo(true);
		},
		mounted() {
			this.initVideoListBoxUi();
		}
	}
</script>

<style>
	.cate-body{
		overflow-y: hidden;
	}
</style>