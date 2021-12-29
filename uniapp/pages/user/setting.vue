<template>
	<view class="pages">
		<view class="cu-bar bg-my-gray solid-bottom" @click="clearCache">
			<!-- #ifndef APP-PLUS -->
			<view class="action fl nav-arrow">
				<text class="cuIcon-form text-grey"></text> 清除数据缓存
			</view>
			<!-- #endif -->
			<!-- #ifdef APP-PLUS -->
			<view class="action fl nav-arrow">
				<text class="cuIcon-form text-grey"></text> 清除缓存
			</view>
			<!-- #endif -->
			<view class="fr">
				<text class="text-grey text-sm video-more-text">{{cacheSize}}</text>
			</view>
		</view>
		<!-- #ifdef APP-PLUS -->
		<view class="cu-bar bg-my-gray solid-bottom" @click="updateVer">
			<view class="action fl nav-arrow">
				<text class="cuIcon-info text-grey"></text> 当前版本
			</view>
			<view class="fr">
				<text class="text-grey text-sm video-more-text">{{version}}</text>
			</view>
		</view>
		<!-- #endif -->
		<!-- 广告 -->
		<Adview :adSwitchKg="adSwitchKg" :homeADList="homeADList"></Adview>
		<view class="padding flex flex-direction bottom-login-btn" v-if="login">
			<button class="cu-btn bg-my-red margin-tb-sm lg" @click="logout">退出登录</button>
		</view>
	</view>
</template>

<script>
	import Adview from '@/components/home-ad/ad-view.vue';
	export default {
		components: {
			Adview
		},
		data() {
			return {
				version: '1.0.0',
				login: true,
				downloadTask: null,
				cacheSize: 0,
				adSwitchKg: 0,
				homeADList: null
			}
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
		methods:{
			logout () {
				let _this = this;
				_this.$http.post('/user/auth/logout', {}).then((res)=>{
					uni.showToast({title: res.msg, icon: 'none'});
					if (res.code == 200) {
						// uni.removeStorageSync('userInfo');
						uni.setStorageSync('userInfo', null);
						uni.switchTab({
							url: '/pages/user/index'
						})
					}
				}).catch((err)=>{
					
				})
			},
			init () {
				let userinfo = uni.getStorageSync('userInfo');
				if (!userinfo) {
					this.login = false;
				}
				// #ifdef APP-PLUS
					this.version = plus.runtime.version;
					this.cacheSize = this.showCache() ;
				// #endif
				// #ifndef APP-PLUS
					this.cacheSize = this.get_cache_size();
				// #endif
			},
			updateVer () {
				this.checkUpdate();
			},
			checkUpdate () {
				var _this = this;
				_this.$http.post('/index/config/updateInfo',{}).then((res)=>{
					if (res.code == 200) {
						let versionInfo = res.data;
						let io = plus.os.name.toLowerCase();
						switch (io) {
							case 'android':
								_this.AndroidCheckUpdate(versionInfo.android);
								break;
							default:
								return ;
						}
					}
				}).catch((err)=>{
					
				})
			}, 
			AndroidCheckUpdate (versionInfo) {
				let _this = this;
				// #ifdef APP-PLUS
					let ver = (plus.runtime.version).split('.').join('');
					if(versionInfo.version.split('.').join('') > ver && versionInfo.atOnce == true){
						uni.showModal({
						    title: '更新提醒',
						    content: '有新的版本发布：'+ versionInfo.version +"\n当前版本：" + plus.runtime.version + "\n是否立即下载更新？",
						    success: function (res) {
						        if (res.confirm) {
									_this.downLoad(versionInfo);
						        }else{
									return false;
								}
						    }
						});
					}else{
						uni.showToast({
							title: '当前为最新版本',
							icon: 'none'
						})
					}
				// #endif
			}, 
			downLoad(versionInfo) {
				// #ifdef APP-PLUS
					var _this = this;
					var w = plus.nativeUI.showWaiting("　　 开始下载...　　 ");
					_this.downloadTask = plus.downloader.createDownload(versionInfo.download_url , {});
					_this.downloadTask.start();
					// 进度条
					_this.downloadTask.addEventListener( "statechanged", function(task,status){
						switch(task.state) {  
							case 1: // 开始  
								w.setTitle("　　 准备下载...　　 ");  
							break;  
							case 2: // 已连接到服务器  
								w.setTitle("　　 已连接到服务器...　　 ");  
							break;  
							case 3:  
								var a = task.downloadedSize/task.totalSize*100;  
								w.setTitle("　　 已下载" + parseInt(a) + "%　　 ");  
							break;  
							case 4: // 下载完成  
								w.close();
								uni.showModal({
								    title: '下载完成',
								    content: '是否立即安装？',
								    success: function (res) {
								        if (res.confirm) {
											_this.installApp();
								        }else{
											return false;
										}
								    }
								});
							break;  
						}  
					});
				// #endif
			},
			installApp () {
				var _this = this;
				// #ifdef APP-PLUS
				if ( _this.downloadTask.state == 4 ) { 
					plus.runtime.install(plus.io.convertLocalFileSystemURL(_this.downloadTask.filename),{},function(){},
					function(error){  
						uni.showToast({  
							title: '安装失败',  
							mask: false,  
							duration: 1500  
						});  
					})  
				} else {
					 uni.showToast({  
						title: '更新失败',  
						mask: false,  
						duration: 1500  
					 });  
				}
				// #endif
			},
			clearCache () {
				let _this = this, con = '';
				// #ifdef APP-PLUS
					con = '是否清除所有缓存（包括下载的视频及所有数据缓存）？';
				// #endif
				// #ifndef APP-PLUS
					con = '是否清除所有数据缓存（包括登录状态、浏览历史等）？若出现“未找到此影片信息”的提示，可尝试此操作';
				// #endif
				uni.showModal({
				    title: '清除缓存',
				    content: con,
				    success: function (res) {
				        if (res.confirm) {
							// #ifdef APP-PLUS
							_this.removeAllDownload();
							// #endif
							_this.removeLocalStorage();
				        }else{
							return false;
						}
				    }
				});
			},
			removeLocalStorage () {
				let _this = this;
				uni.clearStorage();
				setTimeout(function() {
				    _this.cacheSize = _this.get_cache_size();
				}, 500);
			},
			removeAllDownload() {
				// #ifdef APP-PLUS
					let _this = this;
					plus.io.resolveLocalFileSystemURL('_downloads/', function(entry) {  
					        entry.removeRecursively(function(entry) { //递归删除其下的所有文件及子目录  
					            uni.showToast({
					            	title: '缓存清理完成',
									icon: 'none'
					            })
					        }, function(e) {
								uni.showToast({
									title: e.message,
									icon: 'none'
								})
					        });  
					        setTimeout(function() {  
					            _this.showCache();  
					        }, 500);  
					    }, function(e) {  
						uni.showToast({
							title: '文件路径读取失败',
							icon: 'none'
						})
					});
				// #endif
			},
			get_cache_size(){
			    var obj = "";
				obj = uni.getStorageInfoSync().keys;
			    if(obj !== ""){
					var size = 0;
					obj.forEach(v => {
						let item = uni.getStorageSync(v);
						if (item) {
							if (typeof(item) == 'object') {
								size += JSON.stringify(item).length;
							} 
							if (typeof(item) == 'number') {
								size += item.toString().length;
							}
							if (typeof(item) == 'string') {
								size += item.length;
							}
						}
					})
			        return (size / 1024).toFixed(2) + 'KB';
			    }
				return '0KB';
			},
			showCache() {
				let _this = this;
			    plus.io.resolveLocalFileSystemURL('_downloads/', function(entry) { //通过URL参数获取目录对象或文件对象  
			        var fileSize = 0;  
			        var directoryReader = entry.createReader();  
			        directoryReader.readEntries(function(entries) {   //获取当前目录中的所有文件和子目录  
			            for(var i = 0; i < entries.length; i++) {  
			                if(entries[i].isFile) {  
			                    entries[i].file(function(file) {  
			                        fileSize += (file.size * 0.0009766);  
			                    }, function(e) {  
									uni.showToast({
										title: e.message,
										icon: 'none'
									})
			                    });  
			                } else {  
			                    entries[i].getMetadata(function(metadata) {  
			                        fileSize += (metadata.size * 0.0009766); //1字节=0.0009766kb  
			                    }, function() {  
									uni.showToast({
										title: e.message,
										icon: 'none'
									})
			                    });  
			                }  
			            }  
			        }, function(e) {  
			            uni.showToast({
			            	title: '文件读取失败',
							icon: 'none'
			            })
			        });  
					fileSize = _this.get_cache_size();
			        setTimeout(function() {
						if (Math.ceil(fileSize) > 1024) {
							_this.cacheSize = (_this.fomatFloat(Math.ceil(fileSize) / 1024, 2) + parseFloat(fileSize)) + 'M';
						}else{
							_this.cacheSize = (_this.fomatFloat(Math.ceil(fileSize), 2) + parseFloat(fileSize)) + 'Kb';
						}
					}, 500);
			    }, function(e) {  
			        uni.showToast({
			        	title: '文件路径读取失败',
						icon: 'none'
			        })
			    });  
			},
			fomatFloat(num,n){   
			    var f = parseFloat(num);
			    if(isNaN(f)){
			        return false;
			    }   
			    f = Math.round(num*Math.pow(10, n))/Math.pow(10, n); // n 幂   
			    var s = f.toString();
			    var rs = s.indexOf('.');
			    //判定如果是整数，增加小数点再补0
			    if(rs < 0){
			        rs = s.length;
			        s += '.'; 
			    }
			    while(s.length <= rs + n){
			        s += '0';
			    }
			    return s;
			}
		},
		onLoad() {
			this.init();
		}
	}
</script>

<style>
	.bottom-login-btn{
		bottom: 0;
		position: fixed;
		width: 100%;
	}
</style>
