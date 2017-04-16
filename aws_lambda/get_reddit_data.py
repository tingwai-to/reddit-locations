from __future__ import print_function
import get_data
import handle_s3
import handle_rds


def lambda_handler(event, context):
    print("Log stream name: ", context.log_stream_name)
    print("Log group name: ",  context.log_group_name)
    print(event['time'])

    subname = 'earthporn'

    submissions = get_data.reddit_data(subname)
    for post in submissions:
        try:
            print(post['id'])
            if handle_rds.record_exists(post):
                print('{} already exists'.format(post['id']))
                continue
            else:
                canUpload = get_data.save_image(post)

                if canUpload:
                    handle_s3.upload_image(post)
                    handle_rds.insert_metadata(post)

        except Exception as exc:
            print(exc)

    remaining = context.get_remaining_time_in_millis()/1000.
    print('Time elapsed: {} sec'.format(60-remaining))
    print('Time remaining: {} sec'.format(remaining))
