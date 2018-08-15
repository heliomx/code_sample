export default function (value, lang)
{
    return dictionary(lang)[value];
}

function dictionary(lang) {
    let dicts = {
        ClientStatus: {
            I: 'Inativo',
            A: 'Ativo',
            M: 'Migrado'
        },

        RadioType: {
            A: 'Rádio AM',
            F: 'Rádio FM',
            W: 'Rádio Web',
            T: 'TV CNPJ',
            V: 'TV Web'
        },

        ProgramType: {
            S: 'Semanal',
            D: 'Diário'
        },

        ContactStatus: {
            N: 'Novo',
            R: 'Resolvido'
        }
    }

    return dicts[lang];
}