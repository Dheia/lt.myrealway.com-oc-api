export default function (resolve)
{
    return {
        computed: {
            editor()
            {
                return resolve(this).editor
            },
            graph()
            {
                return resolve(this).editor.graph
            },
            model()
            {
                return resolve(this).editor.graph.model
            },
        },
    }
}
