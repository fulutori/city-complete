#coding: utf-8
from PIL import Image

#緯度経度情報読み込み
path = 'data/japan.csv'
with open(path,'r') as f:
	japan = f.readlines()

#生成する画像のサイズ
width = 3500
height = 2500

#イメージ生成
img = Image.new('RGB', (width, height), (0, 0, 0))

for town in japan:
	town = town.split(',')
	y = 4800 - int(float(town[-2]) * 100) #x座標
	x = int(float(town[-1].replace('\n','')) * 100) - 12000 #y座標
	if (y>=0 and y<=2500) and (x>=0 and x<=3500): #座標が画像サイズを超えていないか確認
		img.putpixel((x, y), (255, 0, 0))

img.save('japan.png')