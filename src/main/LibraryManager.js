// Import the functions you need from the SDKs you need
import { firestore } from "./main.js";
import { Book } from "./Book.js"
import { collection, doc, setDoc, getDocs, query, where } from "https://www.gstatic.com/firebasejs/9.2.0/firebase-firestore.js";

console.log(firestore);

// -----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----
// Library Collection
// 

const libraryCollection = collection(firestore, 'Library');

// store data to database
export async function addLibraryBook() {
    const book = new Book(document.getElementById('floatingInputBookName').value,
                            document.getElementById('floatingInputAuthor').value,
                            document.getElementById('floatingInputPublisher').value,
                            document.getElementById('floatingTextareaDescription').value);

    const bookDoc = doc(libraryCollection, book.bookName);

    const bookDocData = {
        Author: book.author,
        Publisher: book.publisher,
        PublishedDate: '2021-11-5',
        Description: book.description
    };

    try {
        await setDoc(bookDoc, bookDocData, { merge: true });
        console.log('成功將資料新增進資料庫');
    } catch (err) {
        console.err('錯誤：${err}');
    }
}

// read data from database
export async function getData() {
    const q = query(libraryCollection, where('Author', '==', 'Akazukin8763'));

    const querySnapshot = await getDocs(q);
    querySnapshot.forEach((doc) => {
        // doc.data() is never undefined for query doc snapshots
        console.log(doc.id, " => ", doc.data());
    });
}