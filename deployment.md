[toc]
# 发布

## 搜铺数据获取

1. 首先通过地图页面获取名字和坐标地址信息
http://www.soupu.com/UIMap/Index.aspx

分析该页面json
http://www.soupu.com/Api/MapData.ashx?action=Default
action=Default表示获取省的数据

http://www.soupu.com/Api/MapData.ashx?action=CityData&province=%E5%B9%BF%E4%B8%9C

action=CityData获取城市级别的数据
province=省的名称

http://www.soupu.com/Api/MapData.ashx?action=CountryData&city=%E6%B7%B1%E5%9C%B3
action=CountryData获取区的数据
city=城市的名称

http://www.soupu.com/Api/MapData.ashx?action=ProjectData&city=%E4%B8%9C%E8%8E%9E&country=%E4%B8%9C%E5%9F%8E%E8%A1%97%E9%81%93

action=ProjectData 获取数据信息
city=城市的名称
country=区域的名称

将数据存入表soupu_map_data表中


2.获取列表中需要类型的数据
http://www.soupu.com/UIPro/BusniessProject.aspx?pid=90&xzid=1&oss=0&page=1
pid=地区信息
xzid=项目类型ID
page=分页信息

操作运行:
./yii soupu-map     //将地图页面的数据入库

./yii soupu-city //将列表数据存为json文件，文件再data/soupu/项目类型ID/page

将数据入库，根据实际需要将json文件中的数据和表soupu_map_data中的数据结合，根据名字查找

## 商多多数据

1. 根据地图页的接口获取json数据  ./yii shang-duo-duo-json

通过接口：POST http://www.shangdd.com/user_website/house/newMapList.do
post提交，
参数：--获取全国所有数据
{
  "params":{
    "regionId": 0
  }
}
返回的每个省的数据中有regionId，获取市的数据就传对应省的regionId；

数据存储的格式的是在商多多文件中，省的名称/市的名称.json


2. 将json数据放入数据库中  ./yii shang-duo-duo-import

在系统集客点中类型设置为“商场”

