#!/usr/bin/env node
/**
 * 用于将大众点评中的数据的坐标转换为百度坐标
 * @param C
 */
function decode(C) {
    var digi = 16;
    var add = 10;
    var plus = 7;
    var cha = 36;
    var I = -1;
    var H = 0;
    var B = "";
    var J = C.length;
    var G = C.charCodeAt(J - 1);
    C = C.substring(0, J - 1);
    J--;
    for (var E = 0; E < J; E++) {
        var D = parseInt(C.charAt(E), cha) - add;
        if (D >= add) {
            D = D - plus
        }
        B += (D).toString(cha);
        if (D > H) {
            I = E;
            H = D
        }
    }
    var A = parseInt(B.substring(0, I), digi);
    var F = parseInt(B.substring(I + 1), digi);
    var L = (A + F - parseInt(G)) / 2;
    var K = (F - L) / 100000;
    L /= 100000;
    return {
        lat: K,
        lng: L
    }
}

function MapabcEncryptToBdmap(gg_lat, gg_lon) {

    var point = {};
    var x_pi = 3.14159265358979324 * 3000.0 / 180.0;
    var x = Number(gg_lon);
    var y = Number(gg_lat);
    var z = Math.sqrt(x * x + y * y) + 0.00002 * Math.sin(y * x_pi);
    var theta = Math.atan2(y, x) + 0.000003 * Math.cos(x * x_pi);
    var bd_lon = z * Math.cos(theta) + 0.0065;
    var bd_lat = z * Math.sin(theta) + 0.006;
    point.lng = bd_lon;
    point.lat = bd_lat;
    //alert("-1:"+point.lng+","+point.lat);
    return point;
}

var poi = process.argv[2];
var point = MapabcEncryptToBdmap(decode(poi).lat, decode(poi).lng);
console.log(point.lng + ',' + point.lat);