<template>
    <div class="app-sidebar">
        <div class="sidebar-menu">
            <div v-for="item in menuItems">
                <router-link :to="item.url"
                             v-text="item.title"
                             :class="['sidebar-link', {'is-active': isActive(item)}]"
                ></router-link>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data()
        {
            return {
                menuItems: [
                    {
                        title: 'Products',
                        url  : '/products',
                    },
                    {
                        title: 'Bundles',
                        url  : '/bundles',
                    },
                    {
                        title: 'Categories',
                        url  : '/categories',
                    },
                    {
                        title: 'Filters',
                        url  : '/filters',
                    },
                    {
                        title: 'Pages',
                        url  : '/genericpages',
                    },
                    {
                        title: 'Sitemap',
                        url  : '/pages',
                    },
                    {
                        title: 'Catalogitems',
                        url  : '/catalogitems',
                    },
                ],
            }
        },
        methods: {
            isActive(item)
            {
                return this.$route.path === item.url
            },
        },
    }
</script>
<style lang="scss">
    .app-sidebar {
        height: 100%;
        background: #777;
    }

    .sidebar-menu {
        padding: 5px 0;
    }

    .sidebar-link {
        display: block;
        padding: 15px 20px;
        color: white;

        &:hover {
            color: white;
            background: rgba(255, 255, 255, .2);
        }

        &.is-active {
            background: rgba(255, 255, 255, .2);
        }
    }
</style>