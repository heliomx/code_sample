
var UNITS = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
var STEP = 1024;

export default function (value, power) {
    return (value / Math.pow(STEP, power)).toFixed(2) + UNITS[power];
}
