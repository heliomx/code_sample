

export function telephone(value)
{
    if (!value) return value;
    let re = /^(\d\d)(\d\d\d\d\d?)(\d\d\d\d)$/;
    return value.replace(re, "($1) $2-$3");
}