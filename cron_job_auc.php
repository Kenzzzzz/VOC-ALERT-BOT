<?php
  require('./libs/database/connect-db.php');
  $payload = [
    "to" => "Ube5f4c7c18af1e4536d7b9bf6b7c15d1",
    "messages" => [
      [
        "type" => "flex",
        "altText" => "ข้อมูลงานคงค้างนานของ กฟต.1-2-3 (สถานะ 18 เม.ย. 2562)",
        "contents" => [
          "type" => "carousel",
          "contents" => [
  
          ]
        ]
      ]
    ]
  ];
  $payload_all_district = [];
  $icon_path = "https://cdn1.iconfinder.com/data/icons/material-core/20/check-circle-outline-512.png";
  $district_map = [
    "J" => [
      "name" => "กฟต.1 - เพชรบุรี", 
      "img_cover_path" => "https://wp-assets.dotproperty-kh.com/wp-content/uploads/sites/9/2016/11/16121211/1394464031_p1.jpg"
    ],
    "K" => [
      "name" => "กฟต.2 - นครศรีธรรมราช",
      "img_cover_path" => "https://mpics.mgronline.com/pics/Images/560000007843701.JPEG"
    ],
    "L" => [
      "name" => "กฟต.3 - ยะลา",
      "img_cover_path" => "https://f.ptcdn.info/242/031/000/1431322326-IMG0640-o.jpg"
    ]
  ];

  foreach($district_map as $district => $data){
    $district_name = $data['name'];
    $district_image = $data['img_cover_path'];
    $district_object = [
      "type" => "bubble",
      "header" => [
        "type" => "box",
        "layout" => "vertical",
        "contents" => [
          [
            "type" => "text",
            "text" => $district_name,
            "weight" => "bold",
            "size" => "md",
            "margin" => "xl",
            "align" => "center"
          ]
        ]
      ],
      "hero" => [
        "type" => "image",
        "url" => $district_image,
        "size" => "full",
        "aspectRatio" => "2:1",
        "aspectMode" => "cover"
      ],
      "body" => [
        "type" => "box",
        "layout" => "vertical",
        "contents" => [
          // วาง 3 ส่วนในการ์ดในนี้คือ ก่อนปี 59, ปี 2559, ปี 2560 และ ปี 2561
        ]
      ]
    ];

    $auc_type = [
      ["criteria" => "< 2559", "desc" => "ก่อนปี 2559"],
      ["criteria" => "2559", "desc" => "ปี 2559"]
    ];
    foreach($auc_type as $index => $data){
      $criteria_year = $data['criteria'];
      $desc = $data['desc'];

      // หาจำนวนงานจาก criteria
      $fetch_sum_of_work = "SELECT * FROM tbl_tran_auc WHERE year ='$criteria_year' AND office_code like '$district%'";
      $fetch_sum_of_work_results = mysqli_query($conn, $fetch_sum_of_work);
      $work_sum = mysqli_num_rows($fetch_sum_of_work_results);

      $each_auc_type = [
        "type" => "box",
        "layout" => "vertical",
        "margin" => "md",
        "spacing" => "sm",
        //ใส่รายละเอียดตั้งแต่หัวข้อคือก่อนปี 2559 จำนวนงานรวม ตัวคั่นและรายละเอียดการไฟฟ้า
        "contents" => [
          //หัวข้อ "ก่อนปี 2559    จำนวนงาน () งาน
          [
            "type" => "box",
            "layout" => "horizontal",
            "contents" => [
              [
                "type" => "text",
                "text" => "$desc",
                "size" => "md",
                "color" => "#000000",
                "weight" => "bold",
                "flex" => 6
              ],
              [
                "type" => "text",
                "text" => "(รวม $work_sum งาน)",
                "size" => "md",
                "color" => "#111111",
                "align" => "end",
                "weight" => "bold",
                "flex" => 4
              ]
            ]
          ],[
            "type" => "separator",
            "margin" => "md"
          ]
          // จำนวนงานไล่มาเป็นลิสต์
          // ใส่ปุ่มให้กดดูรายละเอียด
        ] 
      ];

      // หางานมาใส่ตามลิสต์
      $fetch_list_of_work = "SELECT auc.office_code, office.office_name, COUNT(*) as count_job ".
                            "FROM tbl_tran_auc auc JOIN tbl_auc_office office ON auc.office_code = office.office_code ".
                            "WHERE auc.office_code like '$district%' AND year = '$criteria_year' ".
                            "group by office.office_name ".
                            "order by auc.office_code, auc.office_code ";
      $fetch_list_of_work_results = mysqli_query($conn, $fetch_list_of_work);

      if(mysqli_num_rows($fetch_list_of_work_results) === 0){
        $each_auc_type['contents'][] = [
          "type" => "box",
          "layout" => "horizontal",
          "margin" => "md",
          "contents" => [
            [
              "type" => "box",
              "layout" => "baseline",
              "contents" => [
                [
                  "type" =>  "text",
                  "text" => "ไม่มีงานคงค้าง",
                  "size" => "md",
                  "weight" => "bold",
                  "color" => "#555555",
                  "margin" => "md",
                  "align" => "center"
                ]
              ],
              "flex" => 10
            ]
          ]
        ];
      }

      while($pea_data = $fetch_list_of_work_results->fetch_assoc()){
        $pea_branch_obj = [
          "type" => "box",
          "layout" => "horizontal",
          "margin" => "md",
          "contents" => [
            [
              "type" => "box",
              "layout" => "baseline",
              "contents" => [
                [
                  "type" => "icon",
                  "url" => $icon_path,
                  "size" => "sm"
                ],
                [
                  "type" =>  "text",
                  "text" => "$pea_data[office_name]",
                  "size" => "sm",
                  "color" => "#555555",
                  "margin" => "md"
                ]
              ],
              "flex" => 7
            ],
            [
              "type" => "text",
              "text" => "$pea_data[count_job] งาน",
              "size" => "sm",
              "color" => "#111111",
              "align" => "center",
              "flex" => 3
            ]
          ]
        ];
        // เพิ่มข้อมูลการไฟฟ้า
        $each_auc_type['contents'][] = $pea_branch_obj;
      }
      // เพิ่มปุ่มกดโดยตรวจสอบก่อนว่ามีจำนวนงานในเงื่อนไขหรือไม่
      if($work_sum !== 0){
        $each_auc_type['contents'][] = [
          "type" => "button",
          "margin" => "md",
          "action" => [
            "type" => "uri",
            "label" => "ดูข้อมูล$desc",
            "uri" => "https://voc-bot.herokuapp.com/show_auc_data.php?year=".urlencode($criteria_year)."&district=".urlencode($district)
          ],
          "style" => "primary"
        ];
      }
      // remarks TODO: uncomment 208, 212 - 217
      // $district_object['body']['contents'][] = $each_auc_type;

      // เพิ่มตัวคั่นก่อนปิดท้าย
      // if($index === 0){
        // $district_object['body']['contents'][] = [
        //   "type" => "text",
        //   "text" => "Spacing here",
        //   "size" => "md",
        //   "align" => "center",
        //   "color" => "#FFFFFF"
        // ];
      // }
    }

    // เพิ่มข้อมูลปี 2560 ,2561 และ 2562
    $another_auc_type = [
      ["criteria" => "2560", "desc" => "ปี 2560"],
      ["criteria" => "2561", "desc" => "ปี 2561"],
      ["criteria" => "2562", "desc" => "ปี 2562"]
    ];
    foreach($another_auc_type as $index => $data){
      $criteria_year = $data['criteria'];
      $desc = $data['desc'];

      $each_auc_type = [
        "type" => "box",
        "layout" => "vertical",
        "margin" => "md",
        "spacing" => "sm",
        //ใส่รายละเอียดตั้งแต่หัวข้อคือก่อนปี 2559 จำนวนงานรวม ตัวคั่นและรายละเอียดการไฟฟ้า
        "contents" => [
          //หัวข้อ ปี 2560 และ ปี 2561
          [
            "type" => "box",
            "layout" => "horizontal",
            "contents" => [
              [
                "type" => "text",
                "text" => "$desc",
                "size" => "md",
                "color" => "#000000",
                "weight" => "bold",
                "flex" => 6
              ]
            ]
          ],[
            "type" => "separator",
            "margin" => "md"
          ]
          // จำนวนงานไล่มาเป็นลิสต์
          // ใส่ปุ่มให้กดดูรายละเอียด
        ] 
      ];

      // คำนวณร้อยละการปิดงาน
      $fetch_amount_job = "SELECT * FROM tbl_auc_goal WHERE district = '$district' AND year = '$criteria_year' ".
                          "ORDER BY CASE ".
                          "          WHEN type = 'closed' THEN 1 ".
                          "          WHEN type = 'goal' THEN 2 ".
                          "          ELSE 3 ".
                          "        END ASC ";
      $results = mysqli_query($conn, $fetch_amount_job);

      // เอางานปิดได้ปัจจุบัน
      $row = $results->fetch_assoc();
      $closed_job = $row['amount_job'];
      
      // เอางานปิดตามเป้า
      $row = $results->fetch_assoc();
      $goal_job = $row['amount_job'];
      $percentage_closed_job = number_format(($closed_job/$goal_job)*100, 2, ".", "");

      // เพิ่มปุ่มกดโดยตรวจสอบก่อนว่ามีจำนวนงานในเงื่อนไขหรือไม่
      $each_auc_type['contents'][] = [
        "type" => "button",
        "margin" => "md",
        "height" => "sm",
        "action" => [
          "type" => "uri",
          "label" => "ปิดงานก่อสร้างได้ $percentage_closed_job %",
          "uri" => "https://voc-bot.herokuapp.com/auc_charts.php?year=".urlencode($criteria_year)."&district=".urlencode($district)
        ],
        "style" => "secondary"
      ];

      $district_object['body']['contents'][] = $each_auc_type;

      // เพิ่มต้วเว้นโดยใส่เป็นตัวอักษรสีขาว
      if($index !== count($another_auc_type) - 1){
        $district_object['body']['contents'][] = [
          "type" => "text",
          "text" => "Spacing here",
          "size" => "md",
          "align" => "center",
          "color" => "#FFFFFF"
        ];
      }
    }

    // เอาสามส่วนแต่ละเขตเก็บเข้าไปใน payload
    $payload['messages'][0]['contents']['contents'][] = $district_object;
  }

  print "<pre>";
  // print_r($payload);
  // print_r(json_encode($payload, JSON_PRETTY_PRINT));
  print_r(json_encode($payload, JSON_UNESCAPED_UNICODE));
  print "</pre>";
