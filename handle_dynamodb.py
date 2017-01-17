from __future__ import print_function
import os
import boto3
from boto3.dynamodb.types import TypeSerializer


def upload_metadata(dynamo_item):
    dynamodb = boto3.client('dynamodb', region_name=os.environ['region_name'])
    dynamodb.put_item(TableName=os.environ['table_name'], Item=dynamo_item)
    print(dynamo_item['id']['S'] + ' stored in dynamoDB')

def dict_to_dynamodb_item(data):
    item = TypeSerializer().serialize(data)
    item = item['M']  # remove extra 'M' tag
    return item
