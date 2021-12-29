export default{
    data(){
        return {
            //设置默认的分享参数
            share:{
                title:'',
                path:'',
                imageUrl:'',
                desc:'',
                content:''
            },
			shareTimeLine: {
				title: '',
				query: '',
				imageUrl: ''
			}
        }
    },
    onShareAppMessage(res) {
        return {
            title: this.share.title,
            path: this.share.path,
            imageUrl: this.share.imageUrl,
            desc: this.share.desc,
            content: this.share.content,
            success(res){
                uni.showToast({
                    title:'分享成功'
                })
            },
            fail(res){
                uni.showToast({
                    title:'分享失败',
                    icon:'none'
                })
            }
        }
    },
	onShareTimeline(){
		return {
			title: this.shareTimeLine.title,
			query: this.shareTimeLine.query,
			imageUrl: this.shareTimeLine.imageUrl
		};
	}
}