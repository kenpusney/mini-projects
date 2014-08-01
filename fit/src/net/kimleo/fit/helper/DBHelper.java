package net.kimleo.fit.helper;

import android.content.Context;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

/**
 * Created by Kim Leo on 14-3-28.
 */
public class DBHelper extends SQLiteOpenHelper {

    private static final String DBNAME = "fdb";

    public DBHelper(Context context) {
        super(context, DBNAME, null, 1);
    }

    @Override
    public void onCreate(SQLiteDatabase sqLiteDatabase) {
        String sql = "create table records (" +
                "id integer primary key autoincrement," +
                "date string," +
                "weight double," +
                "rate integer" +
                ");";
        sqLiteDatabase.execSQL(sql);
    }

    @Override
    public void onUpgrade(SQLiteDatabase sqLiteDatabase, int i, int i2) {
    }
}
