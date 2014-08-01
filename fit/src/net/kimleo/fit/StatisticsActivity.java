package net.kimleo.fit;

import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;
import net.kimleo.fit.helper.DBHelper;

import java.util.HashMap;
import java.util.LinkedList;

/**
 * Created by Kim Leo on 14-3-27.
 */
public class StatisticsActivity extends Activity {

    private LinkedList<HashMap<String, String>> statRecords;
    private DBHelper dbHelper;

    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.statistics);

        dbHelper = new DBHelper(this);

        SharedPreferences pref = getSharedPreferences("PREFERENCE", Context.MODE_PRIVATE);
        TextView tv = (TextView) findViewById(R.id.textView);
        tv.setText("This is statistics for " + pref.getString("NAME", "YOU!"));

        ListView list = (ListView) findViewById(R.id.listView);
        statRecords = queryAllRecords();

        list.setAdapter(new SimpleAdapter(getApplicationContext(), statRecords, R.layout.record_item,
                new String[]{"Date", "Weight", "Rate"},
                new int[]{R.id.textDate, R.id.textWeight, R.id.textRate}));
        Button btn = (Button) findViewById(R.id.button);
        btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                clearAllData(view);
            }
        });
    }

    private void clearAllData(View view) {
        SQLiteDatabase db = dbHelper.getWritableDatabase();
        db.delete("records", "1", new String[]{});
        finish();
    }

    private LinkedList<HashMap<String, String>> queryAllRecords() {
        LinkedList<HashMap<String, String>> records = new LinkedList<HashMap<String, String>>();
        SQLiteDatabase db = dbHelper.getReadableDatabase();
        Cursor cursor = db.rawQuery("select * from records order by id desc", null);
        Log.d("KFQUERY", cursor.toString());
        cursor.moveToFirst();
        while (!cursor.isAfterLast()) {
            HashMap<String, String> record = new HashMap<String, String>();
            record.put("Date", String.valueOf(cursor.getString(1)));
            record.put("Weight", String.valueOf(cursor.getDouble(2)));
            record.put("Rate", formatStars(cursor.getInt(3)));
            records.add(record);
            cursor.moveToNext();
        }
        return records;
    }

    private String formatStars(int count) {
        String stars = "";
        if (count >= 5) return "★★★★★";
        if (count <= 0) return "☆☆☆☆☆";

        for (int i = count; i > 0; i--) stars += "★";
        for (int i = 5 - count; i > 0; i--) stars += "☆";

        return stars;
    }
}