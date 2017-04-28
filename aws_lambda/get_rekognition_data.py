"""AWS Lambda handler function

This module is triggered whenever a new file is uploaded to S3. The file is 
put through Rekognition and labels are extracted using object/scene detection.
Labels are then inserted into MySQL table.

"""

from __future__ import print_function
import logging
import boto3
import urllib
import handle_rds


rekognition = boto3.client('rekognition')
logger = logging.getLogger()
logger.setLevel(logging.INFO)


def detect_labels(bucket, key):
    """
    Retrieve labels detected in an image.
    
    Args:
        bucket (str): name of bucket the image is from
        key (str): name of file
    
    Returns:
        response (dict): list of object/scenes and respective confidence
            eg. { "Labels": [ 
                    { "Confidence": 99.9, 
                      "Name": "object1"
                    }, 
                    { "Confidence": 98.8,
                      "Name": "object2"
                    },
                    ...
                    ]
                }
    
    """
    response = rekognition.detect_labels(Image={"S3Object": {"Bucket": bucket, "Name": key}}, MinConfidence=70)
    return response


def lambda_handler(event, context):
    bucket = event['Records'][0]['s3']['bucket']['name']
    key = urllib.unquote_plus(event['Records'][0]['s3']['object']['key'].encode('utf8'))

    print("Log stream name: ", context.log_stream_name)
    print("Log group name: ", context.log_group_name)
    print('Received event: Bucket: {0}, Name: {1}'.format(bucket, key))

    try:
        # Calls rekognition DetectFaces API to detect labels in S3 image
        response = detect_labels(bucket, key)
        print('Detected labels for key: {}'.format(key))

        # Inserts labels into `Tag` and `Tagmap` tables
        handle_rds.insert_tag(key, response)
        print('Inserted labels for key: {}'.format(key))

    except Exception as exc:
        print(exc)

    remaining = context.get_remaining_time_in_millis()/1000.
    print('Time elapsed: {} sec'.format(3-remaining))
    print('Time remaining: {} sec'.format(remaining))
