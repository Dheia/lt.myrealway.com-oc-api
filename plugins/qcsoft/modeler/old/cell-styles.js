export default function (graph)
{
    let nattrs = 0
    let c = mxConstants,
        definitions = {
            entity   : {
                style(cell)
                {
                    let result = {
                        [c.STYLE_SHAPE]         : mxConstants.SHAPE_SWIMLANE,
                        [c.STYLE_PERIMETER]     : mxPerimeter.RectanglePerimeter,
                        [c.STYLE_ALIGN]         : mxConstants.ALIGN_CENTER,
                        [c.STYLE_VERTICAL_ALIGN]: mxConstants.ALIGN_TOP,
                        [c.STYLE_GRADIENTCOLOR] : '#fff',
                        [c.STYLE_FILLCOLOR]     : '#fef',
                        // [c.STYLE_SWIMLANE_FILLCOLOR]: '#ddd',
                        [c.STYLE_STROKECOLOR]   : 'black',
                        [c.STYLE_FONTCOLOR]     : '#000000',
                        [c.STYLE_STROKEWIDTH]   : '1',
                        [c.STYLE_STARTSIZE]     : '28',
                        [c.STYLE_VERTICAL_ALIGN]: 'middle',
                        [c.STYLE_FONTSIZE]      : '12',
                        [c.STYLE_FONTSTYLE]     : 1,
                        [c.STYLE_IMAGE]         : `${window.assetsBase}/images/icons48/table.png`,
                        [c.STYLE_OPACITY]       : '80',
                        [c.STYLE_SHADOW]        : 0,
                    }

                    return result
                },
            },
            attribute: {
                style(cell)
                {
                    nattrs++
                    let result = {
                        [c.STYLE_SHAPE]         : mxConstants.SHAPE_RECTANGLE,
                        [c.STYLE_PERIMETER]     : mxPerimeter.RectanglePerimeter,
                        [c.STYLE_ALIGN]         : mxConstants.ALIGN_LEFT,
                        [c.STYLE_VERTICAL_ALIGN]: mxConstants.ALIGN_MIDDLE,
                        [c.STYLE_FONTCOLOR]     : '#000',
                        [c.STYLE_FILLCOLOR]     : nattrs % 2 ? '#fff' : '#ffe5e5',
                        [c.STYLE_FONTSIZE]      : '12',
                        [c.STYLE_FONTSTYLE]     : 0,
                        [c.STYLE_SPACING_LEFT]  : '4',
                        [c.STYLE_IMAGE_WIDTH]   : '48',
                        [c.STYLE_IMAGE_HEIGHT]  : '48',
                    }

                    return result
                },
            },
        }

    graph.getStylesheet().getCellStyle = function (type, style)
    {
        // console.log('$' + type + ': ', JSON.stringify(style), JSON.stringify(this.styles[type]))
        // console.log('this', this)
        return this.styles[type]
        // console.log('$' + cellClass, JSON.stringify(value), JSON.stringify(definitions[cellClass]))

        // return {}//definitions[cellClass].style()
    }
    // mxCell.prototype.getStyle = function ()
    // {
    //     console.log('graph.getStylesheet().getCellStyle', this.value)
    //     // console.log('$' + cellClass, JSON.stringify(value), JSON.stringify(definitions[cellClass]))
    //
    //     return definitions[cellClass].style()
    // }

    for (let cellClass in definitions)
    {
        if (definitions.hasOwnProperty(cellClass))
        {
            graph.getStylesheet().putCellStyle(cellClass, definitions[cellClass].style())
        }
    }

}
