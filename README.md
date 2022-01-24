# Online Library Platform

## Online Library Platform｜License：© Online Library Platform 2021

This package is licensed under MIT license. See [LICENSE](https://github.com/Akazukin8763/OnlineLibraryPlatform/blob/main/LICENSE) for details.  

/welcome.php（主頁）：
初始畫面會有「線上圖書館、Online Library Platform」等字樣在畫面中
下方有輸入列可以查詢書名（可直接填空按下搜尋即為搜尋全部書籍），便會跳轉至「/search.php」
最上方的導覽列可點選並自動下拉至該系統的各個簡介，之後的所有頁面皆可點選並跳回至主頁
可點擊上方頭像的下拉式選單，內部有「Setting、Trace、Status、History、Favorite、Notification」可連結至各相關頁面
其中有「Log out」，只有登入後的使用者可點擊並登出，訪客點擊後不會發生任何事件

Guest：
　　/search.php（搜尋頁）：
　　一樣可透過上方的輸入列搜尋相關書籍，且也可以透過左側的類別來塞選相關書籍
　　可點擊書籍下方的超連結連入「/view.php」來查看該本書籍的詳細資料

　　/view.php（書籍詳細資料）：
　　左側列出該本書籍的「類別、作者、發行商等資訊」，右上方列出該本書的詳細簡介，右下方則有改本書最新的評論，若沒有則顯示「暫無評論」
　　中間有「預約、前往所有評論」兩顆按鈕
　　預約需登入且沒有任何懲處紀錄才可線上預約，預約完成後可點擊上方頭像的下拉式選單點選「Status」並前往「/status.php」
　　前往所有評論點擊後會跳轉至「/comment.php」

　　/comment.php（書籍評論）：
　　部分資訊與「/view.php」相同不多贅述
　　詳細評論會顯示所有使用者的評論，會由最新的評論顯示至最舊的部分
　　下方的評論使用者需有歸還記錄才可評論，發表後便會更新資訊，使用者可透過再次評論來進行更新評論的動作
　　點擊回到詳細資料後會跳轉至「/view.php」

Admin：
　　/manageBook.php（編輯書籍）：
　　最上方的輸入列同一般搜尋，搜尋後會列出相關書籍資訊
　　表格最右上方有加號按鈕表示管理員可點擊彈出輸入視窗並輸入相關資訊，提交後會有一本新書產生於圖書館中，可連續輸入相同書名的書籍表示含有多本
　　各個書籍資訊最右方有編輯按鈕，按下後彈出編輯視窗，可對其內容進行修改並提交

　　/checkBook.php（借閱書籍）：
　　預設書籍會有各本書籍的「Book ID」在上方（目前有 1 ~ 68 號，其中不含 26 ~ 31）
　　可在上方輸入書籍編號並查詢，左側會有這本書的相關簡介，且右側會顯示與這本書同本書名的其他書籍並顯示各本書籍狀態
　　管理員可在中間輸入「User ID、Book ID」來對這名使用者進行借閱、歸還的動作
　　之後會在下方顯示該名使用這對於此書的狀態「借閱起始日期、歸還日期、歸還期限、懲處日期」等資訊

　　/announcement.php（發布公告）：
　　管理員在輸入列中輸入公告並發送，公告內容會發送至所有使用者的通知內部（不包含管理員職位）

User：
　　/setting.php（個人設定）：
　　可在輸入列中輸入新密碼、名稱並確認，會直接更改密碼、名稱
　　下方有偏好設定可以勾選類別，被勾選的類別在爾後搜尋之後不會顯示在畫面中，全部會被攔截下來

　　/trace.php（書籍追蹤）：
　　左側的輸入列同一般搜尋，搜尋後會列出相關書籍資訊
　　書籍名稱左側的按鈕會直接將書籍列入追蹤，右側會顯示追蹤書籍的狀態
　　可點選追蹤清單各書籍右側的叉叉按鈕來取消追蹤
　　若搜尋的書籍查無資料，則可將搜尋的名稱列入追蹤，之後只要圖書館中新增的書籍名稱中包含該字串，會發送通知提醒該名使用者


　　/status.php（借閱狀態）：
　　顯示該名使用者各個書籍的預約、借閱狀態，可點擊書籍名稱到「/view.php」顯示該本書籍的詳細資料
　　如果是預約則會有預約的順位，若是借閱則會顯示歸還期限

　　/history.php（歷史紀錄）：
　　顯示該名使用者各個書籍的歷史紀錄，可點擊書籍名稱到「/view.php」顯示該本書籍的詳細資料
　　會有使用者借閱此書的起始日期及歸還日期

　　/favorite.php（我的最愛）：
　　左側的輸入列同一般搜尋，搜尋後會列出相關書籍資訊
　　書籍名稱左側的按鈕會直接將書籍列入最愛（會預設加入 DEFAULT 資料夾），右側會顯示資料夾及書籍，可點擊書籍名稱到「/view.php」顯示該本書籍的詳細資料
　　可點選創建清單左側的按鈕來新建暫存資料夾，當畫面刷新或跳轉時該資料夾會消失，除非有書籍移至內部
　　資料夾前方的減號可點選來收起資料夾內的書籍，再次點選加號可展開內容
　　書籍名稱前的齒輪可點選彈出「更新最愛、移除最愛、刪除資料夾」
　　更新最愛可將該本書籍移動至其他的資料夾中，可移到新建的暫存資料夾
　　移除最愛就字面上的意思
　　刪除資料夾會將點擊的該本書所在的資料夾刪除，並會將內部的所有書籍移到「DEFAULT」，使用者無法刪除「DEFAULT」

　　/notification.php（通知）：
　　由新到舊顯示該名使用者的所有通知，所有通知都是自動發送，除了管理員發布的公告
