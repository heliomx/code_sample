<template>
    <router-view></router-view>
</template>

<script>

export default {
    
    created() {
        this.$auth.ready( () => {
            if (this.$auth.check())
            {
                
            } else {
                this.$router.push('/login');
            }
        })
    }
}
</script>

<style lang="scss">
    .form-margin {
        margin-right: 10vw;
        margin-bottom: 40px;
    }

    .btn__content .icon {
        display: inline-flex;
    }

    .picture-input-btn {
        padding: 10px 20px;
    }

    .page-actions {
		margin-top: 40px;
		background-color: #d2eae8;
        padding: 10px 20px;
        border-top: 1px solid #8db7b3;
		text-align: right;
		position: fixed;
		bottom: 0;
		width: 100%;
        left: 0;
        z-index: 2;
    }

    .actions {
        text-align: right;
        margin-top: 20px;
    }

    .line-actions {
        width:1%;
        white-space: nowrap;
    }
    
    .fade-enter {
        opacity: 0;
    }

    .fade-enter-active {
        transition: opacity 0.3s ease;
    }

    .fade-leave {

    }

    .fade-leave-active {
        transition: opacity 0.3s ease;
        opacity: 0;
    }

</style>