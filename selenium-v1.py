# -*- coding: utf-8 -*-
import time
import os
import os
import glob
import numpy
import subprocess
from datetime import date
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from calendar import monthrange

today = str(date.today())

yearExtract = 2019

monthStartExtract = 12
dayStartExtract = 1#+25

monthEndExtract = 12
dayEndExtract = 3#+25

count = 0

# fileNameArray = []

while (monthStartExtract <= 12 and dayStartExtract <= 31):

	turningMonth = False

	if(dayEndExtract >= (monthrange(yearExtract,monthStartExtract))[1]):
		dayEndExtract = monthrange(yearExtract,monthStartExtract)[1]
		turningMonth = True

	if(monthStartExtract == 13):
		break

	toBegin = str(monthStartExtract)+"/"+str(dayStartExtract)+"/"+str(yearExtract)
	toEnd = str(monthEndExtract)+"/"+str(dayEndExtract)+"/"+str(yearExtract)

	if(dayStartExtract < 10):
		dayStartExtractTmp = "0"+str(dayStartExtract)
	else:
		dayStartExtractTmp = str(dayStartExtract)

	if(dayEndExtract < 10):
		dayEndExtractTmp = "0"+str(dayEndExtract)
	else:
		dayEndExtractTmp = str(dayEndExtract)

	if(monthStartExtract < 10):
		monthStartExtractTmp = "0"+str(monthStartExtract)
	else:
		monthStartExtractTmp = str(monthStartExtract)

	if(monthEndExtract < 10):
		monthEndExtractTmp = "0"+str(monthEndExtract)
	else:
		monthEndExtractTmp = str(monthEndExtract)

	fileName = today+'-BTS-'+str(dayStartExtractTmp)+str(monthStartExtractTmp)+'-'+str(dayEndExtractTmp)+str(monthEndExtractTmp)+".xlsx"
	print(fileName)
	# fileNameArray.append(fileName+".xlsx")	


	period = "("+str(dayStartExtractTmp)+"-"+str(monthStartExtractTmp)+"  "+str(dayEndExtractTmp)+"-"+str(monthEndExtractTmp)+")"
	header = str(dayStartExtractTmp)+'/'+str(monthStartExtractTmp)+'-'+str(dayEndExtractTmp)+'/'+str(monthEndExtractTmp)

	if(turningMonth):
		dayStartExtract = 1
		monthStartExtract += 1
		dayEndExtract = 3
		monthEndExtract += 1
		turningMonth = False
	else:
		dayStartExtract += 3
		dayEndExtract += 3

	# fileNameArray.append(fileName)	

	# USUARIO E SENHA DO BTS
	userName = "lcruz"
	password = "@082016Disc"

	# DEFINE O NAVEGADOR A SER USADO ||| NO CASO ||| FIREFOX

	driver = webdriver.Chrome()

	# URL DE INICIO DE NAVEGAÇÃO

	driver.get("http://btsdla.discovery.com/bts6/")

	# SELECIONA E COLOCA USUARIO E A SENHA , 
	# APERTA A TECLA ENTER E DEFINE UM TEMPO DE ESPERA DE 7 SEGUNDOS 
	# PARA CARREGAR A PROXIMA PAGINA ANTES DE EXECUTAR ALGUM COMANDO

	element = driver.find_element_by_name("login")
	element.send_keys(userName)
	element = driver.find_element_by_name("passwd")
	element.send_keys(password)
	element.send_keys(Keys.RETURN)

	ck1 = True
	while(ck1):
		try:
			element = driver.find_element_by_xpath("/html/body/div[2]/div[2]/ul/li[7]/a/img").click()
			ck1 = False
		except:
			ck1 = True

	ck2 = True
	while(ck2):
		try:
			element = driver.find_element_by_xpath("/html/body/div[2]/div[2]/ul/li[8]/ul/li[8]/a").click()
			ck2 = False
		except:
			ck2 = True

	ck3 = True
	while(ck3):
		try:
			element = driver.switch_to.frame("bts-main")
			ck3 = False
		except:
			ck3 = True
	'''
	time.sleep(5)
	element = driver.find_element_by_xpath("/html/body/div[2]/div[2]/ul/li[7]/a/img").click()
	time.sleep(2)
	element = driver.find_element_by_xpath("/html/body/div[2]/div[2]/ul/li[8]/ul/li[8]/a").click()
	time.sleep(2)
	element = driver.switch_to.frame("bts-main")
	time.sleep(2)
	'''
	# SELECIONANDO REGIÃO
	check1 = True
	while(check1):
		try:
			element = driver.find_element_by_xpath('/html/body/div[8]/form/table/tbody/tr[15]/td[2]/button/span[2]').click()
			check1 = False
		except:
			check1 = True

	# SELECIONA BRASIL
	check2 = True
	while(check2):
		try:
			element = driver.find_element_by_xpath('/html/body/div[13]/div/ul/li[1]/a/span[2]').click()
			check2 = False
		except:
			check2 = True
	
	# CLICA FORA PRA FECHAR O SELECT 
	check3 = True
	while(check3):
		try:
			element = driver.find_element_by_xpath('/html/body/div[8]/form/table/tbody/tr[23]/td[2]/select').click()
			check3 = False
		except:
			check3 = True

	# COLOCANDO AS DATAS
	check3 = True
	while(check3):
		try:
			element = driver.find_element_by_xpath('/html/body/div[8]/form/table/tbody/tr[20]/td[2]/input').clear()
			element = driver.find_element_by_xpath('/html/body/div[8]/form/table/tbody/tr[20]/td[2]/input').send_keys(toBegin)

			element = driver.find_element_by_xpath('//*[@id="bookingto"]').clear()
			element = driver.find_element_by_xpath('//*[@id="bookingto"]').send_keys(toEnd)
			check3 = False
		except:
			check3 = True

	#time.sleep(120)
	# INDO PARA A PAGINA DE EXPORT
	check4 = True
	while(check4):
		try:
			element = driver.find_element_by_xpath('/html/body/div[8]/form/div/input[1]').click()
			check4 = False
		except:
			check4 = True

	# CLICANDO PARA EXPORTAR
	check5 = True
	while(check5):
		try:
			element = driver.find_element_by_xpath('/html/body/form/div/div[5]/div/table/tbody/tr/td[1]/table/tbody/tr/td[3]').click()
			check5 = False
		except:
			check5 = True

	# DEFININDO NOME DO ARQUIVO
	check6 = True
	while(check6):
		try:
			element = driver.find_element_by_xpath('//*[@id="reportTitle"]').send_keys()
			element = driver.find_element_by_xpath('//*[@id="export"]').click()
			check6 = False
		except:
			check6 = True

	# VERIFICANDO SE DOWNLOAD FOI REALIZADO

	basepath = '/home/dads/Downloads/'

	check7 = True
	while(check7):		
		cmd = "cd "+basepath+";ls btsexport*.xlsx -t"
		result = subprocess.run(cmd,shell=True,stdout=subprocess.PIPE)
		tmpFile = result.stdout
		file = tmpFile.splitlines()
		numberOfFiles = len(file)
		name = today+"-"+str(count)+period
		tipo = "xlsx"
		
		if( count + 1 == numberOfFiles ):
			time.sleep(30)
			count = count + 1		
			check7 = False	

	cmd = "cd "+basepath+";ls -t"
	result = subprocess.run(cmd,shell=True,stdout=subprocess.PIPE)
	tmpFile = result.stdout

	files = tmpFile.split(b'\n')
	files.pop()

	nCMD = "cd "+basepath+";mv "+files[0].decode('utf-8')+" "+str(fileName)+" 2>&1"
	print(nCMD)
	nResult = subprocess.run(nCMD,shell=True,stdout=subprocess.PIPE)
	print(nResult.stdout)

	driver.close()
