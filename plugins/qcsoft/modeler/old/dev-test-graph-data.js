export default function ()
{
    let iRowHeight = 20

    return [
        {
            style   : 'entity',
            x       : 200,
            y       : 50,
            width   : 150,
            value   : {
                name: 'New entity',
            },
            children: genattrs(4),
        },
        {
            style   : 'entity',
            x       : 100,
            y       : 300,
            width   : 250,
            value   : {
                name: 'New entity 2',
            },
            children: genattrs(3),
        },
        {
            style   : 'entity',
            x       : 30,
            y       : 30,
            width   : 120,
            value   : {
                name: 'New entity 3',
            },
            children: genattrs(7),
        },
    ]

    function genattrs(count)
    {
        return [
            {
                style : 'attribute',
                height: iRowHeight,
                value : {
                    name         : 'id',
                    type         : 'integer',
                    pk           : true,
                    autoincrement: true,
                },
            },
            ...[...Array(count).keys()].map(i =>
            {
                return {
                    style : 'attribute',
                    height: iRowHeight,
                    value : {
                        name: 'attr_' + (i + 1),
                        type: 'string',
                    },
                }
            }),
        ]
    }
}
