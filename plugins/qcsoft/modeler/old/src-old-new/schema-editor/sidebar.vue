<template>
    <div class="sidebar">
        selectedEntities
        <div v-if="selectedEntities" v-text="JSON.stringify(selectedEntities)"></div>
        <div style="border: 1px solid #ddd; margin: 5px 0;"></div>
        selectedAttributes
        <div v-if="selectedAttributes" v-text="JSON.stringify(selectedAttributes)"></div>
        <div style="border: 1px solid #ddd; margin: 5px 0;"></div>
        selectedAttributePath
        <div v-if="selectedAttributePath">
            <div v-for="item in selectedAttributePath"
                 style="border-bottom:1px solid black"
                 v-text="item.name"
            ></div>
        </div>
        <div style="border: 1px solid #ddd; margin: 5px 0;"></div>
        <btn @click="addEntity">+</btn>
        <div v-for="entity in entities" style="margin-bottom: 5px">
            <div v-for="prop in ['name', 'x', 'y', 'width', 'isSelected']" style="margin: 0 0 2px 0">
                <span style="width: 70px; display: inline-block " v-text="prop"></span>
                <input v-model="entity[prop]" v-if="!prop.match(/^is/)"/>
                <input type="checkbox" v-model="entity[prop]"
                       style="width: 20px; height: 20px; vertical-align:-5px; margin: 0" v-else/>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props   : ['entities'],
        methods : {
            addEntity()
            {
                this.entities.push({
                    name      : '',
                    attributes: [],
                    x         : 10,
                    y         : 10,
                    width     : 100,
                    height    : 100,
                })
            },
        },
        computed: {
            selectedAttributePath()
            {
                if (this.selectedEntities.length || this.selectedAttributes.length !== 1)
                {
                    return null
                }

                let result = []

                this.entities.forEach(e =>
                {
                    let selectedAttribute = e.attributes.find(a => a.isSelected)

                    if (selectedAttribute)
                    {
                        result.push(e, selectedAttribute)
                    }
                })

                return result
            },
            selectedEntities()
            {
                return this.entities.filter(e => e.isSelected)
            },
            selectedAttributes()
            {
                let result = []

                this.entities.forEach(e =>
                {
                    result.push(...e.attributes.filter(a => a.isSelected))
                })

                return result
            },
        },
    }
</script>
<style scoped lang="scss">
    .sidebar {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        padding: 10px 12px;
        background: #eee;
    }
</style>
