export default {
    methods: {
        mxCellGetValue()
        {
            // console.log(this.$entity.entity.name, this.attribute.type, this)

            let typeColumn

            switch (this.attribute.type)
            {
                case 'bool':
                case 'boolean':
                    typeColumn = 'bool'
                    break;
                case 'int':
                case 'integer':
                    typeColumn = 'int'
                    break;
                case 'string':
                    typeColumn = 'str'
                    break;
                case 'text':
                    typeColumn = 'txt'
                    break;
                case 'slug':
                    typeColumn = 'slug'
                    break;
                case 'imageUpload':
                    typeColumn = 'img'
                    break;
                default:
                    typeColumn = 'n/a'
            }

            let listClass = [
                this.attribute.sort_order % 2 ? 'even' : 'odd',
                this.$entity.$attributes.length === this.attribute.sort_order ? 'last' : '',
            ]

            let result = []
            result.push(`<div style="width: ${this.$entity.entity.width}px" class="editor-attribute-cell attr-${listClass.join(' attr-')}">`)
            result.push(`<div class="attribute-cell-inner">`)
            result.push(`<div class="attribute-type" style="background: ${typeColor(this.attribute.type)}">${typeColumn}</div>`)
            result.push(`<div class="attribute-info">`)
            result.push(`<div class="info-name">${this.attribute.name}</div>`)
            result.push(`<div class="info-id">(${this.attribute.id})</div>`)
            result.push(`<div class="info-type">${typeColumn === 'n/a' ? this.attribute.type : ''}</div>`)
            result.push(`</div>`)
            result.push(`</div>`)
            result.push(`</div>`)

            function typeColor(type)
            {
                switch (type)
                {
                    case 'bool':
                    case 'boolean':
                        return '#ccdde7'
                    case 'int':
                    case 'integer':
                        return '#d1efff'
                    case 'string':
                        return '#d7ffd7'
                    case 'text':
                        return '#e7faff'
                    case 'slug':
                        return '#d7ffd7'
                    case 'imageUpload':
                        return '#ffdbdb'
                    default:
                        return 'red'
                }
            }

            return result.join('')
        },
    },
}
