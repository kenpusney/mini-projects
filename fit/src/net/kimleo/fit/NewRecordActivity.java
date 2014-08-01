package net.kimleo.fit;

import android.app.Activity;
import android.content.ContentValues;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RatingBar;
import android.widget.Toast;
import net.kimleo.fit.helper.DBHelper;

import java.util.Calendar;

/**
 * Created by Kim Leo on 14-3-28.
 */
public class NewRecordActivity extends Activity {

    private DBHelper dbHelper;

    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.new_record);

        dbHelper = new DBHelper(this);

        Button btn = (Button) findViewById(R.id.button);
        btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                submitNewRecord(view);
            }
        });
    }

    private void submitNewRecord(View view) {
        SQLiteDatabase db = dbHelper.getWritableDatabase();
        ContentValues values = new ContentValues(2);
        EditText editText = (EditText) findViewById(R.id.editText);
        RatingBar ratingBar = (RatingBar) findViewById(R.id.ratingBar);
        try {
            double weight = Double.parseDouble(editText.getText().toString());
            int rate = (int) ratingBar.getRating();
            Calendar c = Calendar.getInstance();
            String value = String.format(
                    "%4d-%02d-%02d", c.get(Calendar.YEAR), c.get(Calendar.MONTH) + 1, c.get(Calendar.DAY_OF_MONTH)
            );
            values.put("date", value);
            values.put("weight", weight);
            values.put("rate", rate);
            long insert = db.insert("records", "", values);
            finish();
        } catch (Exception e) {
            Toast.makeText(view.getContext(), "Unknown format!", Toast.LENGTH_SHORT).show();
        }
    }
}