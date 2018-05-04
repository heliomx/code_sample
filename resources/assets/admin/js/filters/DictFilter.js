export default function (value, lang)
{
    return dictionary(lang)[value];
}

function dictionary(lang) {
    let dicts = {
        ClientStatus: {
            I: 'Inativo',
            A: 'Ativo'
        },

        RadioType: {
            A: 'Rádio AM',
            F: 'Rádio FM',
            W: 'Rádio Web',
        },

        ProgramType: {
            S: 'Semanal',
            D: 'Diário'
        }
    }

    return dicts[lang];
}