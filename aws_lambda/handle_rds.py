from __future__ import print_function
import sys
import rds_config
import pymysql

# RDS settings
rds_host  = rds_config.db_endpoint
username = rds_config.db_username
password = rds_config.db_password
db_name = rds_config.db_name

try:
    conn = pymysql.connect(rds_host, user=username, passwd=password, db=db_name, connect_timeout=5, autocommit=True, charset='utf8')
except:
    print("ERROR: Unexpected error: Could not connect to MySql instance.")
    sys.exit()

print("SUCCESS: Connection to RDS mysql, {}, instance succeeded".format(db_name))

def record_exists(post):
    """
    Checks if record of Reddit post already exists in MySQL table.
    
    Args:
        post (dict): Reddit metadata
        
    Returns:
        (bool): True if record already exists
    
    """
    with conn.cursor() as cur:
        try:
            cur.execute("SELECT EXISTS (SELECT 1 FROM Image WHERE id=%s)", post['id'])
            count = cur.fetchone()[0]
            if count > 0:
                return True
            else:
                return False

        except Exception as exc:
            print(exc)
            return False

def insert_metadata(data):
    """
    Inserts record of Reddit post's metadata into MySQL table.
    
    Args:
        post (dict): Reddit metadata
        
    Returns:
        None
        
    """
    with conn.cursor() as cur:
        try:
            values = [data['thumbnail'], data['score'], data['title'], data['url'],
                      data['author'], data['created_utc'], data['id'], data['subreddit'],
                      data['preview']]

            cmd =  """INSERT INTO Image (
            thumbnail, score, title, url, author, created_utc, id, subreddit, preview
            )
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)"""
            cur.execute(cmd, values)

        except Exception as exc:
            print(exc)
            return

def insert_tag(key, response):
    """
    Inserts new tags, if any, into Tag table. Inserts images and labels into an
    associative table, Tagmap.
    
    Args:
        key (str): filename
        response (dict): list of object/scenes and respective confidence
        
    Returns:
        None
    
    """
    image_id = key.split('/')[1]

    with conn.cursor() as cur:
        for label in response['Labels']:
            try:
                # Insert label into `Tag` and get id from table
                cmd = """INSERT INTO Tag (name) VALUES (%s)
                         ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id),
                         name=%s"""
                cur.execute(cmd, (label['Name'], label['Name']))

                cur.execute("SELECT LAST_INSERT_ID()")
                tag_id = cur.fetchone()[0]

                # Insert label into `Tagmap`, references id's from `Image` and `Tag`
                cmd = """INSERT INTO Tagmap (image_id, tag_id, confidence)
                         VALUES (%s, %s, %s)"""
                cur.execute(cmd, (image_id, tag_id, label['Confidence']))

            except Exception as exc:
                print(exc)
