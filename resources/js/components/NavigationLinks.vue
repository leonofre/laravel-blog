<template>
    <div v-if="more_pages">
        <nav class="navigation-wrapper">
            <a v-for="page in pages" :class="page.current" :href="page.link"></i>{{page.number}}</a>
        </nav>
    </div>
</template>
<script>
    import { serverBus } from '../blog';
    export default {
        data () {
            return {
                more_pages: false,
                pages: []
            }
        },
        mounted () {
            serverBus.$on( 'more-pages', ( data ) => {
                this.more_pages = data.more_pages;
                this.pages      = data.links;
            });
        },
    }
</script>
