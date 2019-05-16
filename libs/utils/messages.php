<?php

  function getBubbleMessages($conn, $today, $complaint_list){
    $count = 0;
    $json = '{
      "type": "flex",
      "altText": "DAILY ALERT : รายงานเรื่องร้องเรียนสถานะรอและกำลังดำเนินการมากกว่าเท่ากับ 10 วัน",
      "contents": {
          "type": "bubble",
          "styles": {
            "footer": {
              "separator": true
            }
          },
          "body": {
            "type": "box",
            "layout": "vertical",
            "contents": [
              {
                "type": "text",
                "text": "DAILY ALERT",
                "weight": "bold",
                "color": "#1DB446",
                "align": "center",
                "size": "md"
              },
              {
                "type": "text",
                "text": "รายงานเรื่องร้องเรียน ",
                "weight": "bold",
                "size": "xl",
                "align": "center",
                "margin": "md"
              },
              {
                "type": "text",
                "text": "ประจำวันที่ '.$today.'",
                "size": "md",
                "color": "#aaaaaa",
                "align": "center",
                "wrap": true
              },
              {
                "type": "separator",
                "margin": "xxl"
              },
              {
                "type": "box",
                "layout": "vertical",
                "margin": "xxl",
                "spacing": "sm",
                "contents": [';
      while($district = $complaint_list->fetch_assoc()){
        $json .=  '{
                      "type": "box",
                      "layout": "horizontal",
                      "contents": [
                        {
                          "type": "text",
                          "text": "'.$district['main_office'].'",
                          "size": "lg",
                          "color": "#555555",
                          "weight": "bold",
                          "flex": 0
                        },
                        {
                          "type": "text",
                          "text": "'.$district['count_complaint'].' เรื่อง",
                          "size": "lg",
                          "color": "#111111",
                          "align": "end"
                        }
                      ]
                    },';
          $count += $district['count_complaint'];
        }
        $json .='
                  {
                    "type": "separator",
                    "margin": "xxl"
                  },
                  {
                    "type": "box",
                    "layout": "horizontal",
                    "margin": "xxl",
                    "contents": [
                      {
                        "type": "text",
                        "text": "รวมทั้งสิ้น",
                        "size": "lg",
                        "weight": "bold",
                        "color": "#555555"
                      },
                      {
                        "type": "text",
                        "text": "'.$count.' เรื่อง",
                        "size": "lg",
                        "color": "#111111",
                        "align": "end"
                      }
                    ]
                  }
                ]
              },
              {
                "type": "separator",
                "margin": "xxl"
              },
              {
                "type": "button",
                "style": "primary",
                "action": {
                  "type": "uri",
                  "label": "รายละเอียดเพิ่มเติม",
                  "uri": "https://voc-bot.herokuapp.com/south.php?NUMBER=@10"
                }
              }
            ]
          }
        }
     }';
    $result = json_decode($json);
    return $result;
  }