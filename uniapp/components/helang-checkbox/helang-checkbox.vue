<template>
	<view class="container" :class="column">
		<view v-for="(v,i) in list" :key="i" >
			<view 
			class="item"
			:class="{ 'active':(type=='radio' && index == i) || (type=='checkbox' && v.checked) }"
			:data-i="i"
			@tap="change"
			>{{v.text}}</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				list:[],
				index:-1,
				type:'checkbox',
				column:''
			};
		},
		methods: {
			change(e){
				let i = Number(e.currentTarget.dataset.i);
				/* 单选框 */
				if(this.type=='radio'){
					this.index = i;
					this.$nextTick(()=>{
						this.$emit("change",this.get());
					})
					return;
				}
				/* 复选框 */
				if(this.list[i].checked){
					this.$set(this.list[i],"checked",false);
				}else{
					this.$set(this.list[i],"checked",true);
				}
				this.$nextTick(()=>{
					this.$emit("change",this.get());
				})
			},
			set(res) {
				let [type,index] = ['checkbox',-1];
				let column = ['','col_1','col_2','col_3']
				if(res.type == 'radio'){
					type = 'radio';
					index = res.index >= 0 ? res.index : -1;
				}
				this.column = (res.column in column) ? column[res.column] : '';
				this.type = type;
				this.index = index;
				this.list = res.list;
			},
			get(){
				/* 单选框 */
				if(this.type=='radio'){
					if(this.index >= 0){
						return this.list[this.index];
					}else{
						return null;
					}
				}
				
				let arr=[];
				this.list.forEach((item,index)=>{
					if(item.checked){
						arr.push(item);
					}
				});
				return arr.length > 0 ? arr : null;
			}
		}
	}
</script>

<style lang="scss" scoped>
.container{
	display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: flex-start;
    align-items: flex-start;
    align-content: flex-start;
	margin-right: -20rpx;
	font-size: 28rpx;
	text-align: center;
	
	&>view{
		margin-bottom: 20rpx;
		padding-right: 20rpx;
		box-sizing: border-box;
	}
	
	&.col_1{
		&>view{
			width: 100%;
		}
	}
	&.col_2{
		&>view{
			width: 50%;
		}
	}
	&.col_3{
		&>view{
			width: 33.3333333%;
		}
	}
	
	.item{
		line-height: 68rpx;
		padding: 0 20rpx;
		box-sizing: border-box;
		border: #45506a solid 1px;
		background-color: #2b3242;
		color: #dae1f2;
		position: relative;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
		
		&.active{
			background-color: #45506a;
			color: #dae1f2;
			border: #45506a solid 1px;
			
			&::before{
				content: '';
				display:block;
				width: 20px;
				height: 20px;
				background-color: #313a4d;
				position: absolute;
				right: -1px;
				bottom: -1px;
				z-index: 1;
				clip-path: polygon(100% 0, 0% 100%, 100% 100%);
			}
			&::after{
				content: '';
				display:block;
				width: 4px;
				height: 8px;
				border-right: #FFFFFF solid 2px;
				border-bottom: #FFFFFF solid 2px;
				transform:rotate(25deg);
				position: absolute;
				right: 2px;
				bottom: 3px;
				z-index: 2;
			}
		}
	}
}
</style>
