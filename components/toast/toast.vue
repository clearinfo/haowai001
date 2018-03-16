<template>
	<div class="zmiti-toast-main-ui" :class='{"hide":msg === ""}'>
		{{msg}}
	</div>
</template>
<script>
	export default {
		props:['msg'],
		data(){
			return {
				
			}
		},
		mounted(){
			
			
		}
	}
</script>
<style>
	.zmiti-toast-main-ui{
		position: fixed;
		background: rgba(0,0,0,.5);
		color:#fff;
		border-radius: .3rem;
		padding:.3rem .5rem;
		left: 50%;
		z-index: 111111;
		top: 50%;
		-webkit-transform:translate3d(-50%,-50%,0);
		-webkit-transition:1s;
	}
	.zmiti-toast-main-ui.hide{
		display: none;
	}
</style>