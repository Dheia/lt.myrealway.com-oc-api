export default function (graph)
{
    let nattrs = 0
    let c = mxConstants,
        definitions = {
            entity            : {
                style(cell)
                {
                    let result = {
                        [c.STYLE_SHAPE]         : mxConstants.SHAPE_SWIMLANE,
                        [c.STYLE_PERIMETER]     : mxPerimeter.RectanglePerimeter,
                        [c.STYLE_ALIGN]         : mxConstants.ALIGN_LEFT,
                        [c.STYLE_VERTICAL_ALIGN]: mxConstants.ALIGN_TOP,
                        [c.STYLE_GRADIENTCOLOR] : '#eee',
                        [c.STYLE_FILLCOLOR]     : '#eee',
                        // [c.STYLE_SWIMLANE_FILLCOLOR]: '#ddd',
                        [c.STYLE_STROKECOLOR]   : 'black',
                        [c.STYLE_FONTCOLOR]     : '#000000',
                        [c.STYLE_STROKEWIDTH]   : '1',
                        [c.STYLE_STARTSIZE]     : '28',
                        [c.STYLE_VERTICAL_ALIGN]: 'middle',
                        [c.STYLE_FONTSIZE]      : '12',
                        [c.STYLE_FONTSTYLE]     : 1,
                        // [c.STYLE_IMAGE]         : `${window.assetsBase}/images/icons48/table.png`,
                        [c.STYLE_OPACITY]       : '80',
                        [c.STYLE_SHADOW]        : 0,
                    }

                    return result
                },
            },
            attribute         : {
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
            relation_oneToMany: {
                style(cell)
                {
                    let result = {
                        ...graph.stylesheet.getDefaultEdgeStyle(),
                        [c.STYLE_LABEL_BACKGROUNDCOLOR]: '#FFFFFF',
                        [c.STYLE_STROKEWIDTH]          : '2',
                        [c.STYLE_ROUNDED]              : true,
                        [c.STYLE_EDGE]                 : mxEdgeStyle.EntityRelation,
                    }

                    return result
                },
            },
            relation_morphOne : {
                style(cell)
                {
                    let result = {
                        ...graph.stylesheet.getDefaultEdgeStyle(),
                        [c.STYLE_LABEL_BACKGROUNDCOLOR]: '#FFFFFF',
                        [c.STYLE_STROKECOLOR]          : '#fa0',
                        [c.STYLE_STROKEWIDTH]          : '2',
                        [c.STYLE_ROUNDED]              : true,
                        [c.STYLE_EDGE]                 : mxEdgeStyle.EntityRelation,
                    }

                    return result
                },
            },
        }

    graph.getStylesheet().getCellStyle = function (type, style)
    {
        // console.log(arguments)
        return this.styles[type]
    }

    for (let cellClass in definitions)
    {
        if (definitions.hasOwnProperty(cellClass))
        {
            graph.getStylesheet().putCellStyle(cellClass, definitions[cellClass].style())
        }
    }

    // let style = graph.stylesheet.getDefaultEdgeStyle();
    // console.log(style)
    // style[c.STYLE_LABEL_BACKGROUNDCOLOR] = '#FFFFFF';
    // style[c.STYLE_STROKEWIDTH] = '2';
    // style[c.STYLE_ROUNDED] = true;
    // style[c.STYLE_EDGE] = mxEdgeStyle.EntityRelation;
    // console.log(style)
}
