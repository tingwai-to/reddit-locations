from __future__ import print_function
import os
import sys
import boto3
import rds_config
import pymysql

# RDS settings
rds_host  = rds_config.db_endpoint
username = rds_config.db_username
password = rds_config.db_password
db_name = rds_config.db_name

try:
    conn = pymysql.connect(rds_host, user=username, passwd=password, db=db_name, connect_timeout=5, autocommit=True)
except:
    print("ERROR: Unexpected error: Could not connect to MySql instance.")
    sys.exit()

print("SUCCESS: Connection to RDS mysql, {}, instance succeeded".format(db_name))

def insert_metadata(data):
    with conn.cursor() as cur:
        try:
            values = [data['thumbnail'], data['score'], data['title'], data['url'],
                      data['author'], data['created_utc'], data['id'], data['subreddit']]

            cmd =  """INSERT INTO Image (
            thumbnail, score, title, url, author, created_utc, id, subreddit
            )
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s)"""
            cur.execute(cmd, values)
        except Exception as exc:
            print(exc)
            return

def insert_tag(response):
    with conn.cursor() as cur:
        for label in response['Labels']:
            try:
                cmd = "INSERT INTO Tag (name) VALUES (%s)"
                cur.execute(cmd, label['Name'])
            except Exception as exc:
                print(exc)

# def insert_tagmap(response):
#     with conn.cursor() as cur:
