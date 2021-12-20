<template>
    <v-main class="Dashboard">
        <v-carousel
        :cycle="true"
        :continuous="true"
        hide-delimiters
        :show-arrows="false"
        height=630px
        class="carousel">   
            <v-carousel-item
            v-for="(item,i) in items"
            :key="i"
            :src="item.src"
            reverse-transition="fade-transition"
            transition="fade-transition"
            ></v-carousel-item>
        </v-carousel>
    </v-main>
</template>

<script>
    export default {
        name: "Dashboard",
        data() {
            return {
                items: [
                    {
                        src: 'https://img.freepik.com/free-photo/restaurant-interior_1127-3394.jpg?size=626&ext=jpg',
                    },
                    {
                        src: 'https://pingpoint.co.id/media/images/PEJATEN_KULINER_3_RESTORAN_COZY_BERKONSEP_TEMAT.original.jpg',
                    },
                    {
                        src: 'https://img.okezone.com/content/2015/08/21/298/1200414/restoran-buffet-hotel-terenak-di-jakarta-WWBI4R7ksK.jpg',
                    },
                    {
                        src: 'https://www.highstreet.co.id/images/uploads/blogs/Artikel%20HSS%20-%20Desain%20Restoran.jpg',
                    },
                ],
            };
        },
        
    };
</script>     
<style>
</style>  