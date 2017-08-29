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
