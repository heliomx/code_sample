import moment from 'moment';

export default function (value, format)
{
    if (value) {
        format = format ? format : 'DD/MM/YYYY';
        return moment(String(value)).format(format);
    }
}