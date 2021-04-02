# TreeDemo

Projekt realizuje mechanizm zarządzania strukturą drzewiastą.

Wykorzystana baza danych to PostgreSQL, connection_string łączący z bazą znajduje się w funkcji getDbConnection() w pliku Functions.php

Skrypt tworzący tabele oraz wstawiający do niej przykładowe inserty znajduje się w pliku struct.sql w folderze SQL.

Po włączenie pliku index.php wyświetla się całe drzewo, z tego miejsca możemy:
- sortować drzewo
- wejść w poszczególne węzły po kliknięciu na ich nazwę
- po najechaniu na nazwę węzłą wyświetli nam się przycisk "Options", po którego kliknięciu uzyskamy dostęp do opcji
- po wpisaniu nazwy w pole "Rename" i kliknięciu "Update" nastąpi zmiana nazyw (zaimplementowana jest walidacja, która nie przepuszca żadnych znaków specjalnych)
- po wybraniu opcji z pola "Change parent" i kliknięciu "Update" nastapi zmiana rodzica (przeniesienie).
- można jednocześnie zmienić nazwę i przenieść węzeł
- po wybraniu opcji "Delete" nastąpi usunięcie węzła
- po wpisaniu nazwy w pole "Enter name" i kliknięciu "Add" nastąpi dodanie nowego węzła (zaimplementowana jest walidacja, która nie przepuszca żadnych znaków specjalnych)

Nie ma możliwości usuwania ani przenoszenia korzenia drzewa.
