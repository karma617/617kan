<template>
	<view class="pages">
		<view class="solid-bottom">
			<view class="cu-form-group">
				<textarea maxlength="-1" :auto-height="true" placeholder="自动读取剪贴板地址或可手动输入视频地址" @click="clear" @focus="clear" v-model="videoUrl"></textarea>
			</view>
			<view class="padding flex flex-direction">
				<button class="cu-btn bg-blue margin-tb-sm lg" @click="goDown" :disabled="isdisabled">下载</button>
			</view>
		</view>
		<view v-if="!videoInfo">
			<view class="solid-bottom">
				<view class="cu-bar bg-my-black">
					<view class="action">
						<text class="cuIcon-title text-blue"></text>支持短视频平台：
					</view>
				</view>
				<view class="bg-my-black padding-lr">
					<view class="flex-sub text-left">
						<view class="text-df padding-5">抖音、快手、微博、微视、皮皮搞笑、美拍、火山、最右、皮皮虾。
						</view>
					</view>
				</view>
			</view>
			<view class="solid-bottom">
				<view class="cu-bar bg-my-black">
					<view class="action">
						<text class="cuIcon-title text-blue"></text>使用说明：
					</view>
				</view>
				<view class="bg-my-black padding-lr">
					<view class="flex-sub text-left">
						<view class="text-df padding-5">1、输入想要下载的视频地址（可以在视频软件中点击分享->复制链接），返回APP后视频地址会自动读取剪贴板填入，点击“下载”即可。</view>
					</view>
				</view>
			</view>
			<view class="solid-bottom">
				<view class="cu-bar bg-my-black">
					<view class="action">
						<text class="cuIcon-title text-blue"></text>注意事项：
					</view>
				</view>
				<view class="bg-my-black padding-lr">
					<view class="flex-sub text-left">
						<view class="text-df padding-5">某些短视频平台算法经常更新，若出现视频无法下载，请联系我们，我们将及时更新接口。</view>
					</view>
				</view>
			</view>
		</view>
		<view v-else>
			<view class="padding text-left shadow radius bg-my-black">
				<view class="padding-2"  v-for="(item,index) in videoContent" :key="index">
					<text class="text-left">
						{{item.text}}
					</text>
				</view>
				<view class="progress-box" v-if="showProgress">
					<progress :percent="loading" show-info stroke-width="3" />
				</view>
			</view>
		</view>
	</view>
</template>
<script>
	export default {
		data(){
			return {
				videoUrl: '',
				videoInfo: '',
				isdisabled: false,
				videoContent: [],
				downloadTask: null,
				showProgress: false,
				loading: 0
			}
		},
		methods:{
			goDown () {
				var _this = this;
				if (_this.isdisabled){
					return false;
				}
				if (_this.videoUrl.length <= 0) {
					uni.showToast({title: '请填写视频地址', icon: "none"});
					return false;
				}
				_this.isdisabled = true;
				_this.videoContent = [];
				_this.$http.post('/videos/videos/downloadVideo',{url:_this.videoUrl}).then((res)=>{
					_this.isdisabled = false;
					if (res.code != 200) {
						uni.showToast({
							title: res.msg || '解析失败',
							icon: 'none'
						})
						return false;
					}
					_this.videoInfo = res.data;
					_this.downloadVideo();
				}).catch((err)=>{
					
				})
			},
			clear () {
				this.videoUrl = '';
			},
			downloadVideo () {
				let _this = this;
				_this.alertText();
			},
			alertText (num = 0) {
				let _this = this;
				let textArr = [
					{text:'解析中...'},
					{text:'解析成功，正在获取视频信息...'},
					{text:'视频作者：' + _this.videoInfo.author},
					{text:'视频标题：' + _this.videoInfo.title},
					{text:'正在获取视频下载地址...'}
				];
				let random = _this.randomNum(800, 2000);
				if (num < textArr.length) {
					_this.videoContent.push(textArr[num]);
					num++;
					setTimeout(function(){
						_this.alertText(num);
					}, random);
				}else{
					_this.downLoad();
				}
			},
			downLoad() {
				// #ifdef APP-PLUS
					var _this = this, header = '';
					// console.log(_this.videoInfo.url[0]);
					plus.downloader.clear();
					_this.downloadTask = plus.downloader.createDownload(_this.videoInfo.url[0],{filename: '_downloads/download/' + (new Date()).valueOf() + '.mp4'});
					if (_this.$os == "android") {
						_this.downloadTask.setRequestHeader('user-agent','Mozilla/5.0 (Linux; Android 4.4.2; Nexus 4 Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.114 Mobile Safari/537.36');
					}
					if (_this.$os == "ios") {
						_this.downloadTask.setRequestHeader('user-agent','Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25');
					}
					_this.downloadTask.start();
					// 进度条
					_this.downloadTask.addEventListener( "statechanged", function(task,status){
						switch(task.state) {  
							// case 0: // 下载任务开始调度
							// 	_this.videoContent.push({text: '获取成功，准备下载...'});
							// break;
							case 1: // 开始  
								_this.videoContent.push({text: '正在连接服务器...'});
							break;  
							case 2: // 已连接到服务器  
								_this.videoContent.push({text: '已连接到服务器，开始下载...'});
								_this.showProgress = true;
							break;  
							case 3:
								_this.loading = parseInt(task.downloadedSize/task.totalSize*100);
								if (100 == _this.loading) {
									_this.showProgress = false;
								}
							break;  
							case 4: // 下载完成
								_this.videoContent.push({text: '下载完成！'});
								uni.saveVideoToPhotosAlbum({
									filePath: _this.downloadTask.filename,
									success: function () {
										_this.videoContent.push({text: '视频已保存到相册！'});
										_this.isdisabled = false;
										// 扣除本地次数
										let userinfo = uni.getStorageSync('userInfo');
										userinfo.number = userinfo.number - 1;
										uni.setStorageSync('userInfo', userinfo);
									},
									complete: function (e) {
										// console.log(e);
									}
								});
							break;  
						}
					});
				// #endif
			},
			randomNum (minNum, maxNum) {
				return parseInt(Math.random() * (maxNum - minNum + 1) + minNum, 10);
			}
		},
		mounted() {
			
		},
		onBackPress() {
			uni.switchTab({
				url: '/pages/user/index'
			})
			return true;
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
				if (userinfo.number < 1) {
					uni.navigateTo({
					    url: '/pages/user/child/paynumber'
					});
				}else{
					// #ifdef APP-PLUS
						uni.getClipboardData({
							success: function (res) {
								let rep = /(http[s]?:\/\/([\w-]+.)+([:\d+])?(\/[\w-\.\/\?%&=]*)?)/gi; 
								let v = res.match(rep);
								if (v) {
									_this.videoUrl = v[0];
								}
							}
						});
					// #endif
				}
			}
		}
	}
</script>